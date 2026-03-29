# Configuración y Entidades de Base de Datos

En base al plan de desarrollo, las entidades y tablas principales son:

## Entidades Principales

| Tabla | Campos Clave | Relaciones |
|-------|-------------|-----------|
| `patients` | id, name, dni, phone, email, dental_notes, created_by | `has_many work_orders` |
| `work_orders` | id, patient_id, technician_id, status, type, amount, due_date, delivered_at | `belongs_to patient, user` · `has_many payments` |
| `work_order_logs` | id, work_order_id, from_status, to_status, user_id, comment, created_at | `belongs_to work_order, user` |
| `cash_movements` | id, type (in/out), amount, category_id, ref_doc, cashier_id, date, notes | `belongs_to user, cash_category` |
| `cash_closures` | id, date, opening_balance, total_in, total_out, closing_balance, user_id | `has_many cash_movements` |
| `bank_accounts` | id, bank_name, account_number, currency, balance | `has_many bank_movements` |
| `bank_movements` | id, bank_account_id, type, amount, description, date, reconciled | `belongs_to bank_account` |
| `invoices` | id, series, number, patient_id, subtotal, igv, total, status, due_date | `has_many invoice_payments` · `has_one work_order` |
| `invoice_payments` | id, invoice_id, amount, paid_at, payment_method, reference | `belongs_to invoice` |
| `expense_reports` | id, user_id, title, status, total, approved_by, approved_at | `has_many expense_lines` |
| `expense_lines` | id, report_id, category_id, amount, description, receipt_path | `belongs_to expense_report` |
| `audit_logs` | id, user_id, model_type, model_id, action, old_values, new_values, ip | Polymorphic en todos los modelos |
| `settings` | id, key, value, group | — |
| `cheques` | id, type, amount, bank_account_id, date_issued, date_due, status | `belongs_to bank_account` |

*Nota: Esta estructura será la base para las migraciones del sistema en Laravel.*