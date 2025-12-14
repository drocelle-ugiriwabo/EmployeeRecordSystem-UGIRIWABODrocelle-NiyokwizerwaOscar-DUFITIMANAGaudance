package ui;

import model.Employee;
import service.EmployeeService;

import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.awt.event.*;
import java.util.List;
import java.util.regex.*;

public class MainFrame extends JFrame {
    private JTextField txtName, txtEmail, txtAge, txtDepartment;
    private JButton btnAdd, btnUpdate, btnDelete, btnClear, btnLoad;
    private JTable table;
    private DefaultTableModel tableModel;
    private EmployeeService service = new EmployeeService();
    private int selectedId = -1;

    public MainFrame() {
        setTitle("Employee Records");
        setSize(800, 500);
        setLocationRelativeTo(null);
        setDefaultCloseOperation(EXIT_ON_CLOSE);
        setLayout(new BorderLayout());

        // Top panel for form
        JPanel panelForm = new JPanel(new GridLayout(5, 2, 10, 10));
        panelForm.setBorder(BorderFactory.createTitledBorder("Employee Form"));

        panelForm.add(new JLabel("Name:"));
        txtName = new JTextField();
        panelForm.add(txtName);

        panelForm.add(new JLabel("Email:"));
        txtEmail = new JTextField();
        panelForm.add(txtEmail);

        panelForm.add(new JLabel("Age:"));
        txtAge = new JTextField();
        panelForm.add(txtAge);

        panelForm.add(new JLabel("Department:"));
        txtDepartment = new JTextField();
        panelForm.add(txtDepartment);

        // Buttons
        btnAdd = new JButton("Add");
        btnUpdate = new JButton("Update");
        btnDelete = new JButton("Delete");
        btnClear = new JButton("Clear");
        btnLoad = new JButton("Load");

        JPanel panelButtons = new JPanel();
        panelButtons.add(btnAdd);
        panelButtons.add(btnUpdate);
        panelButtons.add(btnDelete);
        panelButtons.add(btnClear);
        panelButtons.add(btnLoad);

        panelForm.add(panelButtons);

        add(panelForm, BorderLayout.NORTH);

        // Table
        tableModel = new DefaultTableModel(new String[]{"ID", "Name", "Email", "Age", "Department"}, 0);
        table = new JTable(tableModel);
        JScrollPane scrollPane = new JScrollPane(table);
        add(scrollPane, BorderLayout.CENTER);

        // Button actions
        btnAdd.addActionListener(e -> addEmployee());
        btnLoad.addActionListener(e -> loadEmployees());
        btnUpdate.addActionListener(e -> updateEmployee());
        btnDelete.addActionListener(e -> deleteEmployee());
        btnClear.addActionListener(e -> clearFields());

        // Table row click
        table.addMouseListener(new MouseAdapter() {
            public void mouseClicked(MouseEvent e) {
                int row = table.getSelectedRow();
                selectedId = Integer.parseInt(tableModel.getValueAt(row, 0).toString());
                txtName.setText(tableModel.getValueAt(row, 1).toString());
                txtEmail.setText(tableModel.getValueAt(row, 2).toString());
                txtAge.setText(tableModel.getValueAt(row, 3).toString());
                txtDepartment.setText(tableModel.getValueAt(row, 4).toString());
            }
        });
    }

    private boolean validateInput() {
        if (txtName.getText().isEmpty() || txtEmail.getText().isEmpty() ||
                txtAge.getText().isEmpty() || txtDepartment.getText().isEmpty()) {
            JOptionPane.showMessageDialog(this, "All fields are required!");
            return false;
        }

        // Name letters only
        if (!txtName.getText().matches("[a-zA-Z ]+")) {
            JOptionPane.showMessageDialog(this, "Name can contain letters only");
            return false;
        }

        // Email regex
        String emailRegex = "^[\\w-\\.]+@([\\w-]+\\.)+[\\w-]{2,4}$";
        Pattern pattern = Pattern.compile(emailRegex);
        if (!pattern.matcher(txtEmail.getText()).matches()) {
            JOptionPane.showMessageDialog(this, "Invalid email format");
            return false;
        }

        // Age number
        try {
            int age = Integer.parseInt(txtAge.getText());
            if (age < 18 || age > 65) {
                JOptionPane.showMessageDialog(this, "Age must be between 18 and 65");
                return false;
            }
        } catch (NumberFormatException ex) {
            JOptionPane.showMessageDialog(this, "Age must be a number");
            return false;
        }

        return true;
    }

    private void addEmployee() {
        if (!validateInput()) return;

        Employee emp = new Employee();
        emp.setName(txtName.getText());
        emp.setEmail(txtEmail.getText());
        emp.setAge(Integer.parseInt(txtAge.getText()));
        emp.setDepartment(txtDepartment.getText());

        service.addEmployee(emp);
        JOptionPane.showMessageDialog(this, "Employee Added Successfully!");
        loadEmployees();
        clearFields();
    }

    private void loadEmployees() {
        tableModel.setRowCount(0);
        List<Employee> list = service.getAllEmployees();
        for (Employee e : list) {
            tableModel.addRow(new Object[]{e.getId(), e.getName(), e.getEmail(), e.getAge(), e.getDepartment()});
        }
    }

    private void updateEmployee() {
        if (selectedId == -1) {
            JOptionPane.showMessageDialog(this, "Select a record to update");
            return;
        }
        if (!validateInput()) return;

        Employee emp = new Employee();
        emp.setId(selectedId);
        emp.setName(txtName.getText());
        emp.setEmail(txtEmail.getText());
        emp.setAge(Integer.parseInt(txtAge.getText()));
        emp.setDepartment(txtDepartment.getText());

        service.updateEmployee(emp);
        JOptionPane.showMessageDialog(this, "Employee Updated Successfully!");
        loadEmployees();
        clearFields();
    }

    private void deleteEmployee() {
        if (selectedId == -1) {
            JOptionPane.showMessageDialog(this, "Select a record to delete");
            return;
        }

        int confirm = JOptionPane.showConfirmDialog(this, "Are you sure?", "Delete", JOptionPane.YES_NO_OPTION);
        if (confirm == JOptionPane.YES_OPTION) {
            service.deleteEmployee(selectedId);
            JOptionPane.showMessageDialog(this, "Employee Deleted Successfully!");
            loadEmployees();
            clearFields();
        }
    }

    private void clearFields() {
        txtName.setText("");
        txtEmail.setText("");
        txtAge.setText("");
        txtDepartment.setText("");
        selectedId = -1;
    }

    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> {
            new MainFrame().setVisible(true);
        });
    }
}
