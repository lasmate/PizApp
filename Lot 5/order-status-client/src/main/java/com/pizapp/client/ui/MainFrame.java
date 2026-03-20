package com.pizapp.client.ui;

import com.pizapp.client.db.Database;
import com.pizapp.client.db.DbConfig;
import com.pizapp.client.model.Order;
import com.pizapp.client.model.OrderStatus;
import com.pizapp.client.repository.OrderRepository;

import javax.swing.BorderFactory;
import javax.swing.JButton;
import javax.swing.JComboBox;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTabbedPane;
import javax.swing.JTable;
import javax.swing.ListSelectionModel;
import javax.swing.SwingUtilities;
import java.awt.BorderLayout;
import java.awt.FlowLayout;
import java.awt.event.ActionEvent;
import java.sql.SQLException;
import java.util.List;

public class MainFrame extends JFrame {
    private static final int DEFAULT_ROW_LIMIT = 100;
    private static final int PENDING_STATUS_ID = 4;
    private static final int API_ACCEPT_TARGET_STATUS_ID = 4;
    private static final int API_REFUSE_TARGET_STATUS_ID = 5;
    private static final int API_READY_TARGET_STATUS_ID = 7;

    private final OrderTableModel allOrdersTableModel = new OrderTableModel();
    private final JTable allOrdersTable = new JTable(allOrdersTableModel);
    private final JComboBox<OrderStatus> statusComboBox = new JComboBox<>();
    private final JButton refreshAllButton = new JButton("Rafraîchir");
    private final JButton updateStatusButton = new JButton("Mettre à jour le statut");

    private final OrderTableModel pendingOrdersTableModel = new OrderTableModel();
    private final JTable pendingOrdersTable = new JTable(pendingOrdersTableModel);
    private final JButton refreshPendingButton = new JButton("Rafraîchir en attente");
    private final JButton acceptPendingButton = new JButton("Accepter");
    private final JButton refusePendingButton = new JButton("Refuser");
    private final JButton readyPendingButton = new JButton("Marquer prête");

    private final OrderRepository orderRepository;

    public MainFrame() {
        super("PizApp - Gestion des commandes");

        DbConfig dbConfig = DbConfig.fromClasspath();
        Database database = new Database(dbConfig);
        this.orderRepository = new OrderRepository(database);

        configureWindow();
        buildUi();
        bindActions();

        refreshAllData();
    }

    private void configureWindow() {
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setSize(960, 560);
        setLocationRelativeTo(null);
    }

    private void buildUi() {
        setLayout(new BorderLayout(8, 8));

        JTabbedPane tabbedPane = new JTabbedPane();
        tabbedPane.addTab("Toutes les commandes", buildAllOrdersPanel());
        tabbedPane.addTab("Commandes en attente", buildPendingOrdersPanel());

        add(tabbedPane, BorderLayout.CENTER);
    }

    private JPanel buildAllOrdersPanel() {
        JPanel panel = new JPanel(new BorderLayout(8, 8));

        allOrdersTable.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        allOrdersTable.setFillsViewportHeight(true);

        JPanel topPanel = new JPanel(new BorderLayout());
        topPanel.setBorder(BorderFactory.createEmptyBorder(10, 10, 0, 10));
        topPanel.add(new JLabel("Commandes récentes (tous statuts)"), BorderLayout.WEST);

        JPanel controlsPanel = new JPanel(new FlowLayout(FlowLayout.LEFT));
        controlsPanel.setBorder(BorderFactory.createEmptyBorder(0, 10, 10, 10));

        for (OrderStatus status : OrderStatus.updatableStatuses()) {
            statusComboBox.addItem(status);
        }

        controlsPanel.add(new JLabel("Nouveau statut :"));
        controlsPanel.add(statusComboBox);
        controlsPanel.add(updateStatusButton);
        controlsPanel.add(refreshAllButton);

        panel.add(topPanel, BorderLayout.NORTH);
        panel.add(new JScrollPane(allOrdersTable), BorderLayout.CENTER);
        panel.add(controlsPanel, BorderLayout.SOUTH);

        return panel;
    }

    private JPanel buildPendingOrdersPanel() {
        JPanel panel = new JPanel(new BorderLayout(8, 8));

        pendingOrdersTable.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        pendingOrdersTable.setFillsViewportHeight(true);

        JPanel topPanel = new JPanel(new BorderLayout());
        topPanel.setBorder(BorderFactory.createEmptyBorder(10, 10, 0, 10));
        topPanel.add(new JLabel("Commandes en attente (idetat = 4)"), BorderLayout.WEST);

        JPanel controlsPanel = new JPanel(new FlowLayout(FlowLayout.LEFT));
        controlsPanel.setBorder(BorderFactory.createEmptyBorder(0, 10, 10, 10));
        controlsPanel.add(acceptPendingButton);
        controlsPanel.add(refusePendingButton);
        controlsPanel.add(readyPendingButton);
        controlsPanel.add(refreshPendingButton);

        panel.add(topPanel, BorderLayout.NORTH);
        panel.add(new JScrollPane(pendingOrdersTable), BorderLayout.CENTER);
        panel.add(controlsPanel, BorderLayout.SOUTH);

        return panel;
    }

    private void bindActions() {
        refreshAllButton.addActionListener(this::onRefreshAll);
        updateStatusButton.addActionListener(this::onUpdateStatus);

        refreshPendingButton.addActionListener(this::onRefreshPending);
        acceptPendingButton.addActionListener(this::onAcceptPending);
        refusePendingButton.addActionListener(this::onRefusePending);
        readyPendingButton.addActionListener(this::onReadyPending);
    }

    private void onRefreshAll(ActionEvent event) {
        refreshAllOrders();
    }

    private void onUpdateStatus(ActionEvent event) {
        int selectedRow = allOrdersTable.getSelectedRow();
        if (selectedRow < 0) {
            showError("Sélectionnez une commande dans le tableau.");
            return;
        }

        OrderStatus selectedStatus = (OrderStatus) statusComboBox.getSelectedItem();
        if (selectedStatus == null) {
            showError("Sélectionnez un statut.");
            return;
        }

        Order selectedOrder = allOrdersTableModel.getOrderAt(selectedRow);

        try {
            boolean updated = orderRepository.updateOrderStatus(selectedOrder.getId(), selectedStatus.getId());
            if (!updated) {
                showError("Aucune commande mise à jour.");
                return;
            }

            JOptionPane.showMessageDialog(
                    this,
                    "Commande #" + selectedOrder.getId() + " mise à jour vers '" + selectedStatus.getLabel() + "'.",
                    "Succès",
                    JOptionPane.INFORMATION_MESSAGE
            );
            refreshAllData();
        } catch (SQLException exception) {
            showError("Erreur SQL pendant la mise à jour : " + exception.getMessage());
        }
    }

    private void onRefreshPending(ActionEvent event) {
        refreshPendingOrders();
    }

    private void onAcceptPending(ActionEvent event) {
        updateSelectedPendingOrderStatus(API_ACCEPT_TARGET_STATUS_ID, "Commande acceptée.");
    }

    private void onRefusePending(ActionEvent event) {
        updateSelectedPendingOrderStatus(API_REFUSE_TARGET_STATUS_ID, "Commande refusée.");
    }

    private void onReadyPending(ActionEvent event) {
        updateSelectedPendingOrderStatus(API_READY_TARGET_STATUS_ID, "Commande marquée prête.");
    }

    private void updateSelectedPendingOrderStatus(int statusId, String successMessage) {
        int selectedRow = pendingOrdersTable.getSelectedRow();
        if (selectedRow < 0) {
            showError("Sélectionnez une commande en attente.");
            return;
        }

        Order selectedOrder = pendingOrdersTableModel.getOrderAt(selectedRow);

        try {
            boolean updated = orderRepository.updateOrderStatus(selectedOrder.getId(), statusId);
            if (!updated) {
                showError("Aucune commande mise à jour.");
                return;
            }

            JOptionPane.showMessageDialog(this, successMessage, "Succès", JOptionPane.INFORMATION_MESSAGE);
            refreshAllData();
        } catch (SQLException exception) {
            showError("Erreur SQL pendant la mise à jour : " + exception.getMessage());
        }
    }

    private void refreshAllData() {
        refreshAllOrders();
        refreshPendingOrders();
    }

    private void refreshAllOrders() {
        refreshAllButton.setEnabled(false);

        SwingUtilities.invokeLater(() -> {
            try {
                List<Order> orders = orderRepository.findRecentOrders(DEFAULT_ROW_LIMIT);
                allOrdersTableModel.setOrders(orders);
            } catch (SQLException exception) {
                showError("Erreur SQL lors du chargement : " + exception.getMessage());
            } finally {
                refreshAllButton.setEnabled(true);
            }
        });
    }

    private void refreshPendingOrders() {
        refreshPendingButton.setEnabled(false);

        SwingUtilities.invokeLater(() -> {
            try {
                List<Order> pendingOrders = orderRepository.findRecentOrdersByStatus(PENDING_STATUS_ID, DEFAULT_ROW_LIMIT);
                pendingOrdersTableModel.setOrders(pendingOrders);
            } catch (SQLException exception) {
                showError("Erreur SQL lors du chargement des commandes en attente : " + exception.getMessage());
            } finally {
                refreshPendingButton.setEnabled(true);
            }
        });
    }

    private void showError(String message) {
        JOptionPane.showMessageDialog(this, message, "Erreur", JOptionPane.ERROR_MESSAGE);
    }
}
