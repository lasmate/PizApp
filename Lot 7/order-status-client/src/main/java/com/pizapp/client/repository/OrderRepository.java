package com.pizapp.client.repository;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.List;

import com.pizapp.client.db.Database;
import com.pizapp.client.model.Order;
import com.pizapp.client.model.OrderLine;

public class OrderRepository {
    private static final String SELECT_ORDERS_BASE =
        "SELECT c.idcommande, " +
            "c.date_heure_commande, " +
            "c.montant_ttc, " +
            "u.login_utilisateur, " +
            "c.idetat, " +
            "e.libetat " +
            "FROM commande c " +
            "JOIN utilisateur u ON u.iduser = c.iduser " +
            "JOIN etat e ON e.idetat = c.idetat ";

    private static final String SELECT_ORDERS =
        SELECT_ORDERS_BASE +
            "ORDER BY c.date_heure_commande DESC " +
            "LIMIT ?";

    private static final String SELECT_ORDERS_BY_STATUS =
        SELECT_ORDERS_BASE +
            "WHERE c.idetat = ? " +
            "ORDER BY c.date_heure_commande ASC " +
            "LIMIT ?";

    private static final String UPDATE_ORDER_STATUS =
        "UPDATE commande SET idetat = ? WHERE idcommande = ?";

    private static final String SELECT_ORDER_LINES =
        "SELECT p.nomproduit, l.quantite, l.total_ht " +
            "FROM ligne_de_commande l " +
            "JOIN produit p ON p.idproduit = l.idproduit " +
            "WHERE l.idcommande = ? " +
            "ORDER BY p.nomproduit ASC";

    private final Database database;

    public OrderRepository(Database database) {
        this.database = database;
    }

    public List<Order> findRecentOrders(int limit) throws SQLException {
        List<Order> orders = new ArrayList<>();

        try (Connection connection = database.getConnection();
             PreparedStatement statement = connection.prepareStatement(SELECT_ORDERS)) {
            statement.setInt(1, limit);

            try (ResultSet resultSet = statement.executeQuery()) {
                while (resultSet.next()) {
                    Timestamp timestamp = resultSet.getTimestamp("date_heure_commande");
                    LocalDateTime dateTime = timestamp == null ? null : timestamp.toLocalDateTime();

                    orders.add(new Order(
                            resultSet.getInt("idcommande"),
                            dateTime,
                            resultSet.getBigDecimal("montant_ttc"),
                            resultSet.getString("login_utilisateur"),
                            resultSet.getInt("idetat"),
                            resultSet.getString("libetat")
                    ));
                }
            }
        }

        return orders;
    }

    public List<Order> findRecentOrdersByStatus(int statusId, int limit) throws SQLException {
        List<Order> orders = new ArrayList<>();

        try (Connection connection = database.getConnection();
             PreparedStatement statement = connection.prepareStatement(SELECT_ORDERS_BY_STATUS)) {
            statement.setInt(1, statusId);
            statement.setInt(2, limit);

            try (ResultSet resultSet = statement.executeQuery()) {
                while (resultSet.next()) {
                    Timestamp timestamp = resultSet.getTimestamp("date_heure_commande");
                    LocalDateTime dateTime = timestamp == null ? null : timestamp.toLocalDateTime();

                    orders.add(new Order(
                            resultSet.getInt("idcommande"),
                            dateTime,
                            resultSet.getBigDecimal("montant_ttc"),
                            resultSet.getString("login_utilisateur"),
                            resultSet.getInt("idetat"),
                            resultSet.getString("libetat")
                    ));
                }
            }
        }

        return orders;
    }

    public boolean updateOrderStatus(int orderId, int statusId) throws SQLException {
        try (Connection connection = database.getConnection();
             PreparedStatement statement = connection.prepareStatement(UPDATE_ORDER_STATUS)) {
            statement.setInt(1, statusId);
            statement.setInt(2, orderId);
            return statement.executeUpdate() == 1;
        }
    }

    public List<OrderLine> findOrderLines(int orderId) throws SQLException {
        List<OrderLine> lines = new ArrayList<>();

        try (Connection connection = database.getConnection();
             PreparedStatement statement = connection.prepareStatement(SELECT_ORDER_LINES)) {
            statement.setInt(1, orderId);

            try (ResultSet resultSet = statement.executeQuery()) {
                while (resultSet.next()) {
                    lines.add(new OrderLine(
                            resultSet.getString("nomproduit"),
                            resultSet.getInt("quantite"),
                            resultSet.getBigDecimal("total_ht")
                    ));
                }
            }
        }

        return lines;
    }
}
