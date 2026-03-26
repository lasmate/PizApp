package com.pizapp.client.ui;

import java.awt.BorderLayout;
import java.awt.FlowLayout;
import java.awt.GridLayout;
import java.awt.event.ActionEvent;
import java.math.BigDecimal;
import java.sql.SQLException;
import java.util.List;

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
import javax.swing.JTextArea;
import javax.swing.ListSelectionModel;
import javax.swing.SwingUtilities;
import javax.swing.event.ListSelectionEvent;

import com.pizapp.client.db.Database;
import com.pizapp.client.db.DbConfig;
import com.pizapp.client.model.Order;
import com.pizapp.client.model.OrderLine;
import com.pizapp.client.model.OrderStatus;
import com.pizapp.client.repository.OrderRepository;

public class MainFrame extends JFrame {
    private static final int DEFAULT_ROW_LIMIT = 100;
    private static final int API_ACCEPT_TARGET_STATUS_ID = 4;
    private static final int API_REFUSE_TARGET_STATUS_ID = 5;
    private static final int API_READY_TARGET_STATUS_ID = 7;

    private final OrderTableModel allOrdersTableModel = new OrderTableModel();
    private final JTable allOrdersTable = new JTable(allOrdersTableModel);
    private final JComboBox<OrderStatus> statusComboBox = new JComboBox<>();
    private final JButton refreshAllButton = new JButton("Rafraîchir");
    private final JButton updateStatusButton = new JButton("Mettre à jour le statut");

    private final JTabbedPane tabbedPane = new JTabbedPane();
    private final JButton acceptOrderButton = new JButton("Accepter");
    private final JButton refuseOrderButton = new JButton("Refuser");
    private final JButton readyOrderButton = new JButton("Marquer prête");
    private final JButton refreshDetailsButton = new JButton("Rafraîchir les détails");
    private final JLabel detailOrderIdValue = new JLabel("-");
    private final JLabel detailDateValue = new JLabel("-");
    private final JLabel detailClientValue = new JLabel("-");
    private final JLabel detailAmountValue = new JLabel("-");
    private final JLabel detailStatusValue = new JLabel("-");
    private final JTextArea detailLinesArea = new JTextArea(10, 50);

    private Order selectedOrder;

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

        tabbedPane.addTab("Toutes les commandes", buildAllOrdersPanel());
        tabbedPane.addTab("Détail commande", buildOrderDetailsPanel());

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

    private JPanel buildOrderDetailsPanel() {
        JPanel panel = new JPanel(new BorderLayout(8, 8));

        JPanel topPanel = new JPanel(new BorderLayout());
        topPanel.setBorder(BorderFactory.createEmptyBorder(10, 10, 0, 10));
        topPanel.add(new JLabel("Détail de la commande sélectionnée"), BorderLayout.WEST);

        JPanel detailInfoPanel = new JPanel(new GridLayout(5, 2, 8, 8));
        detailInfoPanel.setBorder(BorderFactory.createEmptyBorder(10, 10, 10, 10));
        detailInfoPanel.add(new JLabel("Commande #"));
        detailInfoPanel.add(detailOrderIdValue);
        detailInfoPanel.add(new JLabel("Date"));
        detailInfoPanel.add(detailDateValue);
        detailInfoPanel.add(new JLabel("Client"));
        detailInfoPanel.add(detailClientValue);
        detailInfoPanel.add(new JLabel("Montant TTC"));
        detailInfoPanel.add(detailAmountValue);
        detailInfoPanel.add(new JLabel("Statut"));
        detailInfoPanel.add(detailStatusValue);

        detailLinesArea.setEditable(false);
        detailLinesArea.setLineWrap(true);
        detailLinesArea.setWrapStyleWord(true);
        detailLinesArea.setText("Sélectionnez une commande dans le premier onglet.");

        JPanel centerPanel = new JPanel(new BorderLayout(8, 8));
        centerPanel.add(detailInfoPanel, BorderLayout.NORTH);
        centerPanel.add(new JScrollPane(detailLinesArea), BorderLayout.CENTER);

        JPanel controlsPanel = new JPanel(new FlowLayout(FlowLayout.LEFT));
        controlsPanel.setBorder(BorderFactory.createEmptyBorder(0, 10, 10, 10));
        controlsPanel.add(acceptOrderButton);
        controlsPanel.add(refuseOrderButton);
        controlsPanel.add(readyOrderButton);
        controlsPanel.add(refreshDetailsButton);

        panel.add(topPanel, BorderLayout.NORTH);
        panel.add(centerPanel, BorderLayout.CENTER);
        panel.add(controlsPanel, BorderLayout.SOUTH);

        return panel;
    }

    private void bindActions() {
        refreshAllButton.addActionListener(this::onRefreshAll);
        updateStatusButton.addActionListener(this::onUpdateStatus);

        refreshDetailsButton.addActionListener(this::onRefreshDetails);
        acceptOrderButton.addActionListener(this::onAcceptOrder);
        refuseOrderButton.addActionListener(this::onRefuseOrder);
        readyOrderButton.addActionListener(this::onReadyOrder);
        allOrdersTable.getSelectionModel().addListSelectionListener(this::onAllOrdersSelectionChanged);
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

    private void onRefreshDetails(ActionEvent event) {
        if (selectedOrder == null) {
            showError("Sélectionnez d'abord une commande dans le premier onglet.");
            return;
        }
        loadOrderDetails(selectedOrder);
    }

    private void onAcceptOrder(ActionEvent event) {
        updateSelectedOrderStatus(API_ACCEPT_TARGET_STATUS_ID, "Commande acceptée.");
    }

    private void onRefuseOrder(ActionEvent event) {
        updateSelectedOrderStatus(API_REFUSE_TARGET_STATUS_ID, "Commande refusée.");
    }

    private void onReadyOrder(ActionEvent event) {
        updateSelectedOrderStatus(API_READY_TARGET_STATUS_ID, "Commande marquée prête.");
    }

    private void onAllOrdersSelectionChanged(ListSelectionEvent event) {
        if (event.getValueIsAdjusting()) {
            return;
        }

        int selectedRow = allOrdersTable.getSelectedRow();
        if (selectedRow < 0) {
            return;
        }

        selectedOrder = allOrdersTableModel.getOrderAt(selectedRow);
        loadOrderDetails(selectedOrder);
        tabbedPane.setSelectedIndex(1);
    }

    private void updateSelectedOrderStatus(int statusId, String successMessage) {
        if (selectedOrder == null) {
            showError("Sélectionnez d'abord une commande dans le premier onglet.");
            return;
        }

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

        if (selectedOrder != null) {
            loadOrderDetails(selectedOrder);
        } else {
            resetDetailsPanel();
        }
    }

    private void refreshAllOrders() {
        refreshAllButton.setEnabled(false);

        SwingUtilities.invokeLater(() -> {
            try {
                List<Order> orders = orderRepository.findRecentOrders(DEFAULT_ROW_LIMIT);
                allOrdersTableModel.setOrders(orders);

                if (selectedOrder != null) {
                    selectedOrder = findOrderById(orders, selectedOrder.getId());
                    if (selectedOrder != null) {
                        loadOrderDetails(selectedOrder);
                    } else {
                        resetDetailsPanel();
                    }
                }
            } catch (SQLException exception) {
                showError("Erreur SQL lors du chargement : " + exception.getMessage());
            } finally {
                refreshAllButton.setEnabled(true);
            }
        });
    }

    private void loadOrderDetails(Order order) {
        detailOrderIdValue.setText(String.valueOf(order.getId()));
        detailDateValue.setText(order.getDateTime() == null ? "-" : order.getDateTime().toString());
        detailClientValue.setText(order.getCustomerLogin());
        detailAmountValue.setText(formatAmount(order.getAmount()));
        detailStatusValue.setText(order.getStatusLabel() + " (" + order.getStatusId() + ")");

        try {
            List<OrderLine> lines = orderRepository.findOrderLines(order.getId());
            detailLinesArea.setText(buildLinesText(lines));
        } catch (SQLException exception) {
            detailLinesArea.setText("Erreur de chargement des lignes : " + exception.getMessage());
        }
    }

    private String buildLinesText(List<OrderLine> lines) {
        if (lines.isEmpty()) {
            return "Aucune ligne de commande.";
        }

        StringBuilder builder = new StringBuilder();
        builder.append("Lignes de commande\n\n");

        for (OrderLine line : lines) {
            builder
                .append("- ")
                .append(line.getProductName())
                .append(" | qté: ")
                .append(line.getQuantity())
                .append(" | total HT: ")
                .append(formatAmount(line.getTotalHt()))
                .append('\n');
        }

        return builder.toString();
    }

    private String formatAmount(BigDecimal amount) {
        if (amount == null) {
            return "-";
        }
        return amount + " €";
    }

    private Order findOrderById(List<Order> orders, int orderId) {
        for (Order order : orders) {
            if (order.getId() == orderId) {
                return order;
            }
        }
        return null;
    }

    private void resetDetailsPanel() {
        selectedOrder = null;
        detailOrderIdValue.setText("-");
        detailDateValue.setText("-");
        detailClientValue.setText("-");
        detailAmountValue.setText("-");
        detailStatusValue.setText("-");
        detailLinesArea.setText("Sélectionnez une commande dans le premier onglet.");
    }

    private void showError(String message) {
        JOptionPane.showMessageDialog(this, message, "Erreur", JOptionPane.ERROR_MESSAGE);
    }
}
