package com.pizapp.client.model;

import java.util.Arrays;
import java.util.List;

public enum OrderStatus {
    EN_ATTENTE(4, "en attente"),
    ABANDONNEE(5, "abandonnée"),
    EN_PREPARATION(6, "en préparation"),
    PRETE(7, "prête"),
    SERVIE(8, "servie");

    private final int id;
    private final String label;

    OrderStatus(int id, String label) {
        this.id = id;
        this.label = label;
    }

    public int getId() {
        return id;
    }

    public String getLabel() {
        return label;
    }

    @Override
    public String toString() {
        return label;
    }

    public static OrderStatus fromId(int id) {
        for (OrderStatus status : values()) {
            if (status.id == id) {
                return status;
            }
        }
        throw new IllegalArgumentException("Statut inconnu : " + id);
    }

    public static List<OrderStatus> updatableStatuses() {
        return Arrays.asList(ABANDONNEE, EN_PREPARATION, PRETE, SERVIE);
    }
}
