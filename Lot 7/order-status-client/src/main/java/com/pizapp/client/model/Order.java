package com.pizapp.client.model;

import java.math.BigDecimal;
import java.time.LocalDateTime;

public class Order {
    private final int id;
    private final LocalDateTime dateTime;
    private final BigDecimal amount;
    private final String customerLogin;
    private final int statusId;
    private final String statusLabel;

    public Order(int id,
                 LocalDateTime dateTime,
                 BigDecimal amount,
                 String customerLogin,
                 int statusId,
                 String statusLabel) {
        this.id = id;
        this.dateTime = dateTime;
        this.amount = amount;
        this.customerLogin = customerLogin;
        this.statusId = statusId;
        this.statusLabel = statusLabel;
    }

    public int getId() {
        return id;
    }

    public LocalDateTime getDateTime() {
        return dateTime;
    }

    public BigDecimal getAmount() {
        return amount;
    }

    public String getCustomerLogin() {
        return customerLogin;
    }

    public int getStatusId() {
        return statusId;
    }

    public String getStatusLabel() {
        return statusLabel;
    }
}
