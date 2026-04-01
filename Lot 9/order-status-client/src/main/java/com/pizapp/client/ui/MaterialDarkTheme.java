package com.pizapp.client.ui;

import java.awt.Color;
import java.util.Enumeration;

import javax.swing.UIDefaults;
import javax.swing.UIManager;
import javax.swing.UnsupportedLookAndFeelException;
import javax.swing.plaf.FontUIResource;

// Global dark theme defaults shared by Swing components.
public final class MaterialDarkTheme {
    private static final Color BACKGROUND = new Color(0x12, 0x14, 0x1A);
    private static final Color SURFACE = new Color(0x1E, 0x22, 0x2B);
    private static final Color SURFACE_ALT = new Color(0x2A, 0x30, 0x3C);
    private static final Color FOREGROUND = new Color(0xE6, 0xE9, 0xEF);
    private static final Color ACCENT = new Color(0x8A, 0xB4, 0xF8);
    private static final Color ACCENT_FG = new Color(0x10, 0x17, 0x24);

    private MaterialDarkTheme() {
    }

    // Applies look-and-feel + app-wide UI defaults.
    public static void apply() {
        try {
            UIManager.setLookAndFeel(UIManager.getCrossPlatformLookAndFeelClassName());
        } catch (ClassNotFoundException | InstantiationException | IllegalAccessException | UnsupportedLookAndFeelException ignored) {
        }

        UIDefaults defaults = UIManager.getDefaults();
        FontUIResource baseFont = new FontUIResource("SansSerif", FontUIResource.PLAIN, 15);
        FontUIResource titleFont = new FontUIResource("SansSerif", FontUIResource.BOLD, 16);

        Enumeration<Object> keys = defaults.keys();
        while (keys.hasMoreElements()) {
            Object key = keys.nextElement();
            Object value = defaults.get(key);
            if (value instanceof FontUIResource) {
                defaults.put(key, baseFont);
            }
        }

        UIManager.put("Panel.background", BACKGROUND);
        UIManager.put("Viewport.background", BACKGROUND);
        UIManager.put("TabbedPane.background", SURFACE);
        UIManager.put("TabbedPane.foreground", FOREGROUND);
        UIManager.put("TabbedPane.selected", SURFACE_ALT);
        UIManager.put("TabbedPane.contentAreaColor", BACKGROUND);
        UIManager.put("Label.foreground", FOREGROUND);
        UIManager.put("Label.font", titleFont);
        UIManager.put("TextArea.background", SURFACE);
        UIManager.put("TextArea.foreground", FOREGROUND);
        UIManager.put("TextArea.caretForeground", FOREGROUND);
        UIManager.put("TextArea.selectionBackground", SURFACE_ALT);
        UIManager.put("TextArea.selectionForeground", FOREGROUND);
        UIManager.put("ComboBox.background", SURFACE_ALT);
        UIManager.put("ComboBox.foreground", FOREGROUND);
        UIManager.put("ComboBox.selectionBackground", ACCENT);
        UIManager.put("ComboBox.selectionForeground", ACCENT_FG);
        UIManager.put("Table.background", SURFACE);
        UIManager.put("Table.foreground", FOREGROUND);
        UIManager.put("Table.selectionBackground", SURFACE_ALT);
        UIManager.put("Table.selectionForeground", FOREGROUND);
        UIManager.put("Table.gridColor", SURFACE_ALT);
        UIManager.put("TableHeader.background", SURFACE_ALT);
        UIManager.put("TableHeader.foreground", FOREGROUND);
        UIManager.put("ScrollPane.background", BACKGROUND);
        UIManager.put("OptionPane.background", BACKGROUND);
        UIManager.put("OptionPane.messageForeground", FOREGROUND);
        UIManager.put("Button.background", ACCENT);
        UIManager.put("Button.foreground", ACCENT_FG);
        UIManager.put("Button.select", SURFACE_ALT);
    }
}
