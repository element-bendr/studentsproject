# UX/UI Designer Output

## Design System

- Typography: system-ui; readable contrast; 16px base.
- Spacing: 4/8/16/24px scale; consistent paddings/margins.
- Buttons: primary `.btn` with accessible colors; hover brightness.
- Cards: `.card` with subtle border, rounded corners, padding.
- Tables: `.table` with clear headers, borders, and cell spacing.
- Alerts: `.alert.success` and `.alert.error` for feedback.
- Layout: shared navbar/footer via `includes/header.php` and `includes/footer.php`.
- Accessibility: labels, focus states, keyboard navigable.

## Wireframe Descriptions

- Public pages: hero/title, content sections; navbar links to core pages; footer.
- Student register/login: simple forms with labels; CSRF hidden field; success/error alerts.
- Student dashboard: cards for details, active count, attendance table, downloads table, notices cards.
- Admin dashboard: metrics cards, quick links.
- Admin attendance: mark form + by-date and by-student views.
- Admin uploads: upload form; recent uploads table.
- Admin notices: form + recent list.
- Admin enquiries/appointments: table + email filter input.
