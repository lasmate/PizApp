package com.pizapp.client.model;

import java.math.BigDecimal;

public class OrderLine {
    private final String productName;
    private final int quantity;
    private final BigDecimal totalHt;

    public OrderLine(String productName, int quantity, BigDecimal totalHt) {
        this.productName = productName;
        this.quantity = quantity;
        this.totalHt = totalHt;
    }

    public String getProductName() {
        return productName;
    }

    public int getQuantity() {
        return quantity;
    }

    public BigDecimal getTotalHt() {
        return totalHt;
    }
}
