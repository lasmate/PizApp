package com.pizapp.client.ui;

import java.awt.BorderLayout;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.FlowLayout;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.GridLayout;
import java.awt.Insets;
import java.awt.RenderingHints;
import java.math.BigDecimal;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

import javax.swing.BorderFactory;
import javax.swing.JButton;
import javax.swing.JComboBox;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.JTextArea;
import javax.swing.ListSelectionModel;
import javax.swing.SwingUtilities;
import javax.swing.border.EmptyBorder;

import com.pizapp.client.db.Database;
import com.pizapp.client.db.DbConfig;
import com.pizapp.client.model.Order;
import com.pizapp.client.model.OrderLine;
import com.pizapp.client.model.OrderStatus;
import com.pizapp.client.repository.OrderRepository;

public class MainFrame extends JFrame {
    private static final int DEFAULT_ROW_LIMIT = 100;
    private static final int DETAIL_ROW_LIMIT = 500;
    private static final int API_ACCEPT_TARGET_STATUS_ID = 4;
    private static final int API_REFUSE_TARGET_STATUS_ID = 5;
    private static final int API_READY_TARGET_STATUS_ID = 7;

    private static final Color BACKGROUND = new Color(0x12, 0x14, 0x1A);
    private static final Color SURFACE = new Color(0x1E, 0x22, 0x2B);
    private static final Color SURFACE_ALT = new Color(0x2A, 0x30, 0x3C);
    private static final Color FOREGROUND = new Color(0xE6, 0xE9, 0xEF);
    private static final Color MUTED_FOREGROUND = new Color(0xB5, 0xBD, 0xCC);
    private static final Color ACCENT = new Color(0x8A, 0xB4, 0xF8);
    private static final Color ACCENT_HOVER = new Color(0xA0, 0xC2, 0xF9);
    private static final Color ACCENT_FOREGROUND = new Color(0x10, 0x17, 0x24);

    private final OrderTableModel allOrdersTableModel = new OrderTableModel();
    private final JTable allOrdersTable = new JTable(allOrdersTableModel);
    private final JComboBox<OrderStatus> statusComboBox = new JComboBox<>();
    private final JButton refreshAllButton = new PillButton("Rafraîchir", SURFACE_ALT, FOREGROUND, new Color(0x35, 0x3D, 0x4D));
    private final JButton updateStatusButton = new PillButton("Mettre à jour le statut", ACCENT, ACCENT_FOREGROUND, ACCENT_HOVER);
    private final JButton openDetailsButton = new PillButton("Ouvrir les détails sélectionnés", SURFACE_ALT, FOREGROUND, new Color(0x35, 0x3D, 0x4D));

    private final OrderRepository orderRepository;

    public MainFrame() {
        super("PizApp - Gestion des commandes");

        DbConfig dbConfig = DbConfig.fromClasspath();
        Database database = new Database(dbConfig);
        this.orderRepository = new OrderRepository(database);

        configureWindow();
        buildUi();
        bindActions();
        applyMaterialStyling();

        refreshAllOrders();
    }

    private void configureWindow() {
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setSize(1080, 680);
        setLocationRelativeTo(null);
        getContentPane().setBackground(BACKGROUND);
    }

    private void buildUi() {
        setLayout(new BorderLayout(8, 8));
        ((JPanel) getContentPane()).setBorder(new EmptyBorder(12, 12, 12, 12));

        add(buildAllOrdersPanel(), BorderLayout.CENTER);
    }

    private JPanel buildAllOrdersPanel() {
        JPanel panel = new JPanel(new BorderLayout(8, 8));
        panel.setBackground(BACKGROUND);

        allOrdersTable.setSelectionMode(ListSelectionModel.MULTIPLE_INTERVAL_SELECTION);
        allOrdersTable.setFillsViewportHeight(true);

        JPanel topPanel = new JPanel(new BorderLayout());
        topPanel.setBackground(BACKGROUND);
        topPanel.setBorder(BorderFactory.createEmptyBorder(10, 10, 0, 10));
        topPanel.add(new JLabel("Commandes récentes (cochez celles à ouvrir)"), BorderLayout.WEST);

        JPanel controlsPanel = new JPanel(new FlowLayout(FlowLayout.LEFT));
        controlsPanel.setBackground(BACKGROUND);
        controlsPanel.setBorder(BorderFactory.createEmptyBorder(0, 10, 10, 10));

        for (OrderStatus status : OrderStatus.updatableStatuses()) {
            statusComboBox.addItem(status);
        }

        controlsPanel.add(new JLabel("Nouveau statut :"));
        controlsPanel.add(statusComboBox);
        controlsPanel.add(updateStatusButton);
        controlsPanel.add(openDetailsButton);
        controlsPanel.add(refreshAllButton);

        JScrollPane tableScrollPane = new JScrollPane(allOrdersTable);
        tableScrollPane.setBorder(BorderFactory.createEmptyBorder());

        panel.add(topPanel, BorderLayout.NORTH);
        panel.add(tableScrollPane, BorderLayout.CENTER);
        panel.add(controlsPanel, BorderLayout.SOUTH);

        return panel;
    }

    private void applyMaterialStyling() {
        Font baseFont = new Font(Font.SANS_SERIF, Font.PLAIN, 16);
        Font buttonFont = new Font(Font.SANS_SERIF, Font.BOLD, 16);

        allOrdersTable.setFont(baseFont);
        allOrdersTable.setForeground(FOREGROUND);
        allOrdersTable.setBackground(SURFACE);
        allOrdersTable.setSelectionBackground(SURFACE_ALT);
        allOrdersTable.setSelectionForeground(FOREGROUND);
        allOrdersTable.setRowHeight(38);
        allOrdersTable.getTableHeader().setFont(new Font(Font.SANS_SERIF, Font.BOLD, 15));
        allOrdersTable.getTableHeader().setBackground(SURFACE_ALT);
        allOrdersTable.getTableHeader().setForeground(FOREGROUND);

        if (allOrdersTable.getColumnModel().getColumnCount() > 0) {
            allOrdersTable.getColumnModel().getColumn(0).setMinWidth(90);
            allOrdersTable.getColumnModel().getColumn(0).setMaxWidth(110);
            allOrdersTable.getColumnModel().getColumn(0).setPreferredWidth(100);
        }

        statusComboBox.setFont(baseFont);
        statusComboBox.setPreferredSize(new Dimension(220, 42));
        statusComboBox.setBackground(SURFACE_ALT);
        statusComboBox.setForeground(FOREGROUND);

        refreshAllButton.setFont(buttonFont);
        updateStatusButton.setFont(buttonFont);
        openDetailsButton.setFont(buttonFont);
        openDetailsButton.setPreferredSize(new Dimension(300, 46));
    }

    private void bindActions() {
        refreshAllButton.addActionListener(event -> onRefreshAll());
        updateStatusButton.addActionListener(event -> onUpdateStatus());
        openDetailsButton.addActionListener(event -> onOpenSelectedDetails());
    }

    private void onRefreshAll() {
        refreshAllOrders();
    }

    private void onUpdateStatus() {
        List<Order> selectedOrders = getOrdersForStatusUpdate();
        if (selectedOrders.isEmpty()) {
            showError("Cochez une ou plusieurs commandes (ou sélectionnez une ligne) pour mettre à jour le statut.");
            return;
        }

        OrderStatus selectedStatus = (OrderStatus) statusComboBox.getSelectedItem();
        if (selectedStatus == null) {
            showError("Sélectionnez un statut.");
            return;
        }

        int updatedCount = 0;

        for (Order order : selectedOrders) {
            try {
                boolean updated = orderRepository.updateOrderStatus(order.getId(), selectedStatus.getId());
                if (updated) {
                    updatedCount++;
                }
            } catch (SQLException exception) {
                showError("Erreur SQL pendant la mise à jour : " + exception.getMessage());
                return;
            }
        }

        JOptionPane.showMessageDialog(
                this,
                updatedCount + " commande(s) mise(s) à jour vers '" + selectedStatus.getLabel() + "'.",
                "Succès",
                JOptionPane.INFORMATION_MESSAGE
        );

        allOrdersTableModel.clearSelection();
        refreshAllOrders();
    }

    private List<Order> getOrdersForStatusUpdate() {
        List<Order> selectedOrders = new ArrayList<>(allOrdersTableModel.getSelectedOrders());
        if (!selectedOrders.isEmpty()) {
            return selectedOrders;
        }

        int row = allOrdersTable.getSelectedRow();
        if (row >= 0) {
            selectedOrders.add(allOrdersTableModel.getOrderAt(row));
        }
        return selectedOrders;
    }

    private void onOpenSelectedDetails() {
        List<Order> selectedOrders = allOrdersTableModel.getSelectedOrders();
        if (selectedOrders.isEmpty()) {
            showError("Cochez au moins une commande pour ouvrir les détails.");
            return;
        }

        for (Order order : selectedOrders) {
            openOrderDetailsWindow(order);
        }
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

    private void openOrderDetailsWindow(Order order) {
        JFrame detailsFrame = new JFrame("Commande #" + order.getId());
        detailsFrame.setSize(760, 540);
        detailsFrame.setLocationByPlatform(true);
        detailsFrame.getContentPane().setBackground(BACKGROUND);

        JLabel orderIdValue = new JLabel("-");
        JLabel dateValue = new JLabel("-");
        JLabel clientValue = new JLabel("-");
        JLabel amountValue = new JLabel("-");
        JLabel statusValue = new JLabel("-");

        JTextArea linesArea = new JTextArea(10, 50);
        linesArea.setEditable(false);
        linesArea.setLineWrap(true);
        linesArea.setWrapStyleWord(true);

        JPanel infoPanel = new JPanel(new GridLayout(5, 2, 8, 8));
        infoPanel.setBackground(SURFACE);
        infoPanel.setBorder(new EmptyBorder(12, 12, 12, 12));
        infoPanel.add(new JLabel("Commande #"));
        infoPanel.add(orderIdValue);
        infoPanel.add(new JLabel("Date"));
        infoPanel.add(dateValue);
        infoPanel.add(new JLabel("Client"));
        infoPanel.add(clientValue);
        infoPanel.add(new JLabel("Montant TTC"));
        infoPanel.add(amountValue);
        infoPanel.add(new JLabel("Statut"));
        infoPanel.add(statusValue);

        JScrollPane linesScrollPane = new JScrollPane(linesArea);
        linesScrollPane.setBorder(BorderFactory.createEmptyBorder());

        JButton acceptButton = new PillButton("Accepter", ACCENT, ACCENT_FOREGROUND, ACCENT_HOVER);
        JButton refuseButton = new PillButton("Refuser", new Color(0xD7, 0x70, 0x79), new Color(0x29, 0x0F, 0x12), new Color(0xDF, 0x87, 0x8F));
        JButton readyButton = new PillButton("Marquer prête", new Color(0x81, 0xC9, 0x95), new Color(0x0F, 0x22, 0x16), new Color(0x95, 0xD4, 0xA6));
        JButton refreshButton = new PillButton("Rafraîchir", SURFACE_ALT, FOREGROUND, new Color(0x35, 0x3D, 0x4D));

        Font baseFont = new Font(Font.SANS_SERIF, Font.PLAIN, 16);
        Font buttonFont = new Font(Font.SANS_SERIF, Font.BOLD, 16);

        orderIdValue.setFont(baseFont);
        dateValue.setFont(baseFont);
        clientValue.setFont(baseFont);
        amountValue.setFont(baseFont);
        statusValue.setFont(baseFont);

        orderIdValue.setForeground(MUTED_FOREGROUND);
        dateValue.setForeground(MUTED_FOREGROUND);
        clientValue.setForeground(MUTED_FOREGROUND);
        amountValue.setForeground(MUTED_FOREGROUND);
        statusValue.setForeground(MUTED_FOREGROUND);

        linesArea.setFont(baseFont);
        linesArea.setForeground(FOREGROUND);
        linesArea.setBackground(SURFACE);
        linesArea.setBorder(new EmptyBorder(12, 12, 12, 12));

        acceptButton.setFont(buttonFont);
        refuseButton.setFont(buttonFont);
        readyButton.setFont(buttonFont);
        refreshButton.setFont(buttonFont);

        JPanel controlsPanel = new JPanel(new FlowLayout(FlowLayout.LEFT));
        controlsPanel.setBackground(BACKGROUND);
        controlsPanel.setBorder(BorderFactory.createEmptyBorder(0, 10, 10, 10));
        controlsPanel.add(acceptButton);
        controlsPanel.add(refuseButton);
        controlsPanel.add(readyButton);
        controlsPanel.add(refreshButton);

        JPanel centerPanel = new JPanel(new BorderLayout(8, 8));
        centerPanel.setBackground(BACKGROUND);
        centerPanel.setBorder(new EmptyBorder(10, 10, 10, 10));
        centerPanel.add(infoPanel, BorderLayout.NORTH);
        centerPanel.add(linesScrollPane, BorderLayout.CENTER);

        detailsFrame.setLayout(new BorderLayout(8, 8));
        detailsFrame.add(centerPanel, BorderLayout.CENTER);
        detailsFrame.add(controlsPanel, BorderLayout.SOUTH);

        Runnable reloadDetails = () -> {
            try {
                Order refreshedOrder = reloadOrderById(order.getId());
                if (refreshedOrder == null) {
                    detailsFrame.dispose();
                    showError("La commande #" + order.getId() + " n'est plus disponible.");
                    refreshAllOrders();
                    return;
                }
                loadOrderDetails(refreshedOrder, orderIdValue, dateValue, clientValue, amountValue, statusValue, linesArea);
            } catch (SQLException exception) {
                JOptionPane.showMessageDialog(detailsFrame, "Erreur SQL lors du chargement : " + exception.getMessage(), "Erreur", JOptionPane.ERROR_MESSAGE);
            }
        };

        acceptButton.addActionListener(event -> updateOrderStatusFromDetails(detailsFrame, order.getId(), API_ACCEPT_TARGET_STATUS_ID, "Commande acceptée.", reloadDetails));
        refuseButton.addActionListener(event -> updateOrderStatusFromDetails(detailsFrame, order.getId(), API_REFUSE_TARGET_STATUS_ID, "Commande refusée.", reloadDetails));
        readyButton.addActionListener(event -> updateOrderStatusFromDetails(detailsFrame, order.getId(), API_READY_TARGET_STATUS_ID, "Commande marquée prête.", reloadDetails));
        refreshButton.addActionListener(event -> reloadDetails.run());

        reloadDetails.run();
        detailsFrame.setVisible(true);
    }

    private void updateOrderStatusFromDetails(JFrame detailsFrame, int orderId, int targetStatusId, String successMessage, Runnable reloadDetails) {
        try {
            boolean updated = orderRepository.updateOrderStatus(orderId, targetStatusId);
            if (!updated) {
                JOptionPane.showMessageDialog(detailsFrame, "Aucune commande mise à jour.", "Erreur", JOptionPane.ERROR_MESSAGE);
                return;
            }

            JOptionPane.showMessageDialog(detailsFrame, successMessage, "Succès", JOptionPane.INFORMATION_MESSAGE);
            reloadDetails.run();
            refreshAllOrders();
        } catch (SQLException exception) {
            JOptionPane.showMessageDialog(detailsFrame, "Erreur SQL pendant la mise à jour : " + exception.getMessage(), "Erreur", JOptionPane.ERROR_MESSAGE);
        }
    }

    private Order reloadOrderById(int orderId) throws SQLException {
        List<Order> latestOrders = orderRepository.findRecentOrders(DETAIL_ROW_LIMIT);
        return findOrderById(latestOrders, orderId);
    }

    private void loadOrderDetails(
            Order order,
            JLabel orderIdValue,
            JLabel dateValue,
            JLabel clientValue,
            JLabel amountValue,
            JLabel statusValue,
            JTextArea linesArea) {
        orderIdValue.setText(String.valueOf(order.getId()));
        dateValue.setText(order.getDateTime() == null ? "-" : order.getDateTime().toString());
        clientValue.setText(order.getCustomerLogin());
        amountValue.setText(formatAmount(order.getAmount()));
        statusValue.setText(order.getStatusLabel() + " (" + order.getStatusId() + ")");

        try {
            List<OrderLine> lines = orderRepository.findOrderLines(order.getId());
            linesArea.setText(buildLinesText(lines));
        } catch (SQLException exception) {
            linesArea.setText("Erreur de chargement des lignes : " + exception.getMessage());
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

    private void showError(String message) {
        JOptionPane.showMessageDialog(this, message, "Erreur", JOptionPane.ERROR_MESSAGE);
    }

    private static class PillButton extends JButton {
        private final Color backgroundColor;
        private final Color hoverColor;

        private PillButton(String text, Color backgroundColor, Color foregroundColor, Color hoverColor) {
            super(text);
            this.backgroundColor = backgroundColor;
            this.hoverColor = hoverColor;

            setContentAreaFilled(false);
            setBorderPainted(false);
            setFocusPainted(false);
            setOpaque(false);
            setForeground(foregroundColor);
            setMargin(new Insets(10, 18, 10, 18));
            setPreferredSize(new Dimension(210, 46));
        }

        @Override
        protected void paintComponent(Graphics graphics) {
            Graphics2D graphics2d = (Graphics2D) graphics.create();
            graphics2d.setRenderingHint(RenderingHints.KEY_ANTIALIASING, RenderingHints.VALUE_ANTIALIAS_ON);

            Color fillColor = getModel().isRollover() ? hoverColor : backgroundColor;
            if (!isEnabled()) {
                fillColor = backgroundColor.darker();
            }

            graphics2d.setColor(fillColor);
            graphics2d.fillRoundRect(0, 0, getWidth(), getHeight(), getHeight(), getHeight());
            graphics2d.dispose();

            super.paintComponent(graphics);
        }
    }
}
