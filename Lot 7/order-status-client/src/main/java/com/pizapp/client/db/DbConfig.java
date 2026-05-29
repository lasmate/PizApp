package com.pizapp.client.db;

import java.io.IOException;
import java.io.InputStream;
import java.util.Properties;

public class DbConfig {
    private final String url;
    private final String user;
    private final String password;

    private DbConfig(String url, String user, String password) {
        this.url = url;
        this.user = user;
        this.password = password;
    }

    public static DbConfig fromClasspath() {
        Properties properties = new Properties();
        try (InputStream inputStream = DbConfig.class.getClassLoader().getResourceAsStream("db.properties")) {
            if (inputStream == null) {
                throw new IllegalStateException("Fichier db.properties introuvable dans les ressources.");
            }
            properties.load(inputStream);
        } catch (IOException exception) {
            throw new IllegalStateException("Impossible de lire db.properties", exception);
        }

        String url = properties.getProperty("db.url");
        String user = properties.getProperty("db.user");
        String password = properties.getProperty("db.password", "");

        if (url == null || user == null) {
            throw new IllegalStateException("db.url et db.user sont requis dans db.properties");
        }

        return new DbConfig(url.trim(), user.trim(), password);
    }

    public String getUrl() {
        return url;
    }

    public String getUser() {
        return user;
    }

    public String getPassword() {
        return password;
    }
}
