USE library_management;
INSERT INTO users(username,email,password_hash,role,full_name) VALUES
('admin','admin@example.test','$2y$12$PaAx.OQpwdpnJEu275kK7O7L7qtm0QHKksfWTy.Spjsarz/6M7lJO','admin','System Administrator'),
('librarian','librarian@example.test','$2y$12$VCghCGMDRs3WYhd1wi.Ev.iD2IsUIsAbeLW0i5NxpU2oE/X/9ZDKq','librarian','Morgan Librarian'),
('student','student@example.test','$2y$12$TUZ1ZXIOC77uXbWGGGPWTuZM5IBYJigaBn0z/crxxuxSMx5oE5zZK','student','Alex Student');
INSERT INTO librarians(user_id,employee_number) SELECT user_id,'EMP-001' FROM users WHERE username='librarian';
INSERT INTO students(user_id,student_number,department) SELECT user_id,'STU-001','Computer Applications' FROM users WHERE username='student';
INSERT INTO categories(category_name,description) VALUES ('Computer Science','Programming, computing and information technology'),('Literature','Fiction, poetry and classic literature'),('Science','General science and mathematics'),('Reference','Dictionaries, encyclopedias and reference material');
INSERT INTO books(category_id,isbn,title,author,publisher,total_copies,available_copies) VALUES
((SELECT category_id FROM categories WHERE category_name='Computer Science'),'9780132350884','Clean Code','Robert C. Martin','Prentice Hall',4,4),
((SELECT category_id FROM categories WHERE category_name='Computer Science'),'9780131103627','The C Programming Language','Brian W. Kernighan and Dennis M. Ritchie','Prentice Hall',3,3),
((SELECT category_id FROM categories WHERE category_name='Science'),'9788173711466','A Brief History of Time','Stephen Hawking','Bantam',2,2),
((SELECT category_id FROM categories WHERE category_name='Literature'),'9780141439518','Pride and Prejudice','Jane Austen','Penguin Classics',3,3),
((SELECT category_id FROM categories WHERE category_name='Reference'),'9780198611868','Oxford English Dictionary','Oxford Languages','Oxford',1,1);
INSERT INTO system_settings(setting_key,setting_value) VALUES ('max_books_per_student','3'),('loan_period_days','14'),('daily_fine_rate','2');
