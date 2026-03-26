package com.pizapp.client.ui;

import java.math.BigDecimal;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;
import java.util.Set;

import javax.swing.table.AbstractTableModel;

import com.pizapp.client.model.Order;

// Table model with a checkbox column to track multi-selection by order ID.
public class OrderTableModel extends AbstractTableModel {
    private static final String[] COLUMNS = {
        "Sélection", "ID", "Date", "Montant TTC", "Client", "Statut"
    };

    private static final DateTimeFormatter DATE_FORMATTER = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm");

    private List<Order> orders = new ArrayList<>();
    private final Set<Integer> selectedOrderIds = new HashSet<>();

    // Replaces current data and keeps only valid checked IDs.
    public void setOrders(List<Order> orders) {
        this.orders = new ArrayList<>(orders);

        Set<Integer> existingIds = new HashSet<>();
        for (Order order : this.orders) {
            existingIds.add(order.getId());
        }
        selectedOrderIds.retainAll(existingIds);

        fireTableDataChanged();
    }

    public Order getOrderAt(int rowIndex) {
        return orders.get(rowIndex);
    }

    // Returns checked orders in current table order.
    public List<Order> getSelectedOrders() {
        List<Order> selectedOrders = new ArrayList<>();

        for (Order order : orders) {
            if (selectedOrderIds.contains(order.getId())) {
                selectedOrders.add(order);
            }
        }

        return selectedOrders;
    }

    public void clearSelection() {
        selectedOrderIds.clear();
        fireTableDataChanged();
    }

    @Override
    public int getRowCount() {
        return orders.size();
    }

    @Override
    public int getColumnCount() {
        return COLUMNS.length;
    }

    @Override
    public String getColumnName(int column) {
        return COLUMNS[column];
    }

    @Override
    public Class<?> getColumnClass(int columnIndex) {
        if (columnIndex == 0) {
            return Boolean.class;
        }
        return String.class;
    }

    @Override
    public boolean isCellEditable(int rowIndex, int columnIndex) {
        return columnIndex == 0;
    }

    @Override
    public void setValueAt(Object value, int rowIndex, int columnIndex) {
        if (columnIndex != 0 || rowIndex < 0 || rowIndex >= orders.size()) {
            return;
        }

        Order order = orders.get(rowIndex);
        boolean selected = Boolean.TRUE.equals(value);

        if (selected) {
            selectedOrderIds.add(order.getId());
        } else {
            selectedOrderIds.remove(order.getId());
        }

        fireTableCellUpdated(rowIndex, columnIndex);
    }

    @Override
    public Object getValueAt(int rowIndex, int columnIndex) {
        Order order = orders.get(rowIndex);

        switch (columnIndex) {
            case 0:
                return selectedOrderIds.contains(order.getId());
            case 1:
                return String.valueOf(order.getId());
            case 2:
                return order.getDateTime() == null ? "" : DATE_FORMATTER.format(order.getDateTime());
            case 3:
                return formatAmount(order.getAmount());
            case 4:
                return order.getCustomerLogin();
            case 5:
                return order.getStatusLabel() + " (" + order.getStatusId() + ")";
            default:
                return "";
        }
    }

    private String formatAmount(BigDecimal amount) {
        if (amount == null) {
            return "";
        }
        return amount + " €";
    }
}
