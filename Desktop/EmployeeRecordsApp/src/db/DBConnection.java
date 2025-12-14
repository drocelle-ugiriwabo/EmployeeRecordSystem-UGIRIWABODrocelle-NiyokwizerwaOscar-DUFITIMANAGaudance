package db;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class DBConnection {
    private static final String URL = "jdbc:sqlite:C:/sqlite/employee.db";

    public static Connection getConnection() throws SQLException {
        try {
            // Load the SQLite JDBC driver (required in some Java versions)
            Class.forName("org.sqlite.JDBC");
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        }

        return DriverManager.getConnection(URL);
    }
}
