package com.pizapp.client.ui;

import java.math.BigDecimal;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.List;

import javax.swing.table.AbstractTableModel;

import com.pizapp.client.model.Order;

public class OrderTableModel extends AbstractTableModel {
    private static final String[] COLUMNS = {
            "ID", "Date", "Montant TTC", "Client", "Statut"
    };

    private static final DateTimeFormatter DATE_FORMATTER = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm");

    private List<Order> orders = new ArrayList<>();

    public void setOrders(List<Order> orders) {
        this.orders = new ArrayList<>(orders);
        fireTableDataChanged();
    }

    public Order getOrderAt(int rowIndex) {
        return orders.get(rowIndex);
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
    public Object getValueAt(int rowIndex, int columnIndex) {
        Order order = orders.get(rowIndex);

        switch (columnIndex) {
            case 0:
                return order.getId();
            case 1:
                return order.getDateTime() == null ? "" : DATE_FORMATTER.format(order.getDateTime());
            case 2:
                return formatAmount(order.getAmount());
            case 3:
                return order.getCustomerLogin();
            case 4:
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
