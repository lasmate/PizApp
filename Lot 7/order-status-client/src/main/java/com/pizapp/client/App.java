package com.pizapp.client;

import javax.swing.SwingUtilities;

import com.pizapp.client.ui.MainFrame;
import com.pizapp.client.ui.MaterialDarkTheme;

public class App {
    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> {
            MaterialDarkTheme.apply();

            MainFrame frame = new MainFrame();
            frame.setVisible(true);
        });
    }
}
