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

    private final OrderTableModel tableModel = new OrderTableModel();
    private final JTable ordersTable = new JTable(tableModel);
    private final JComboBox<OrderStatus> statusComboBox = new JComboBox<>();
    private final JButton refreshButton = new JButton("Rafraîchir");
    private final JButton updateStatusButton = new JButton("Mettre à jour le statut");

    private final OrderRepository orderRepository;

    public MainFrame() {
        super("PizApp - Gestion des commandes");

        DbConfig dbConfig = DbConfig.fromClasspath();
        Database database = new Database(dbConfig);
        this.orderRepository = new OrderRepository(database);

        configureWindow();
        buildUi();
        bindActions();

        refreshOrders();
    }

    private void configureWindow() {
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setSize(960, 560);
        setLocationRelativeTo(null);
    }

    private void buildUi() {
        setLayout(new BorderLayout(8, 8));

        ordersTable.setSelectionMode(ListSelectionModel.SINGLE_SELECTION);
        ordersTable.setFillsViewportHeight(true);

        JPanel topPanel = new JPanel(new BorderLayout());
        topPanel.setBorder(BorderFactory.createEmptyBorder(10, 10, 0, 10));
        topPanel.add(new JLabel("Commandes récentes"), BorderLayout.WEST);

        JPanel controlsPanel = new JPanel(new FlowLayout(FlowLayout.LEFT));
        controlsPanel.setBorder(BorderFactory.createEmptyBorder(0, 10, 10, 10));

        for (OrderStatus status : OrderStatus.updatableStatuses()) {
            statusComboBox.addItem(status);
        }

        controlsPanel.add(new JLabel("Nouveau statut :"));
        controlsPanel.add(statusComboBox);
        controlsPanel.add(updateStatusButton);
        controlsPanel.add(refreshButton);

        add(topPanel, BorderLayout.NORTH);
        add(new JScrollPane(ordersTable), BorderLayout.CENTER);
        add(controlsPanel, BorderLayout.SOUTH);
    }

    private void bindActions() {
        refreshButton.addActionListener(this::onRefresh);
        updateStatusButton.addActionListener(this::onUpdateStatus);
    }

    private void onRefresh(ActionEvent event) {
        refreshOrders();
    }

    private void onUpdateStatus(ActionEvent event) {
        int selectedRow = ordersTable.getSelectedRow();
        if (selectedRow < 0) {
            showError("Sélectionnez une commande dans le tableau.");
            return;
        }

        OrderStatus selectedStatus = (OrderStatus) statusComboBox.getSelectedItem();
        if (selectedStatus == null) {
            showError("Sélectionnez un statut.");
            return;
        }

        Order selectedOrder = tableModel.getOrderAt(selectedRow);

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
            refreshOrders();
        } catch (SQLException exception) {
            showError("Erreur SQL pendant la mise à jour : " + exception.getMessage());
        }
    }

    private void refreshOrders() {
        refreshButton.setEnabled(false);

        SwingUtilities.invokeLater(() -> {
            try {
                List<Order> orders = orderRepository.findRecentOrders(DEFAULT_ROW_LIMIT);
                tableModel.setOrders(orders);
            } catch (SQLException exception) {
                showError("Erreur SQL lors du chargement : " + exception.getMessage());
            } finally {
                refreshButton.setEnabled(true);
            }
        });
    }

    private void showError(String message) {
        JOptionPane.showMessageDialog(this, message, "Erreur", JOptionPane.ERROR_MESSAGE);
    }
}
