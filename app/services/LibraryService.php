<?php
declare(strict_types=1);
final class LibraryService
{
    public static function issue(int $bookId, int $studentId, int $librarianId): void
    {
        $pdo=db(); $pdo->beginTransaction();
        try {
            $s=$pdo->prepare("SELECT student_id, status FROM students WHERE student_id=? FOR UPDATE"); $s->execute([$studentId]); $student=$s->fetch();
            if (!$student || $student['status']!=='active') throw new RuntimeException('Student account is not active.');
            $limit=(int)setting('max_books_per_student',3);
            $count=$pdo->prepare("SELECT COUNT(*) FROM book_transactions WHERE student_id=? AND status='issued'"); $count->execute([$studentId]);
            if ((int)$count->fetchColumn()>=$limit) throw new RuntimeException("Borrowing limit of {$limit} active books reached.");
            $b=$pdo->prepare('SELECT book_id, available_copies FROM books WHERE book_id=? AND status="active" FOR UPDATE'); $b->execute([$bookId]); $book=$b->fetch();
            if (!$book || (int)$book['available_copies']<1) throw new RuntimeException('No available copy of this book.');
            $days=(int)setting('loan_period_days',14); $issue=date('Y-m-d'); $due=date('Y-m-d',strtotime("+{$days} days"));
            $ins=$pdo->prepare("INSERT INTO book_transactions(book_id,student_id,librarian_id,issue_date,due_date,status) VALUES(?,?,?,?,?,'issued')");
            $ins->execute([$bookId,$studentId,$librarianId,$issue,$due]);
            $pdo->prepare('UPDATE books SET available_copies=available_copies-1 WHERE book_id=?')->execute([$bookId]);
            $pdo->commit();
        } catch(Throwable $e) { if($pdo->inTransaction()) $pdo->rollBack(); throw $e; }
    }
    public static function returnBook(int $transactionId, int $librarianId): float
    {
        $pdo=db(); $pdo->beginTransaction();
        try {
            $q=$pdo->prepare("SELECT transaction_id,book_id,due_date,status FROM book_transactions WHERE transaction_id=? FOR UPDATE"); $q->execute([$transactionId]); $t=$q->fetch();
            if (!$t || $t['status']!=='issued') throw new RuntimeException('This loan is not currently issued.');
            $today=new DateTimeImmutable('today'); $due=new DateTimeImmutable($t['due_date']);
            $days=max(0,(int)$due->diff($today)->format('%r%a'));
            $rate=(float)setting('daily_fine_rate',2); $fine=round($days*$rate,2);
            $pdo->prepare("UPDATE book_transactions SET return_date=CURDATE(),status='returned',returned_by=? WHERE transaction_id=?")->execute([$librarianId,$transactionId]);
            $pdo->prepare('UPDATE books SET available_copies=available_copies+1 WHERE book_id=?')->execute([$t['book_id']]);
            if ($fine>0) $pdo->prepare("INSERT INTO fines(transaction_id,amount,payment_status) VALUES(?,?,'unpaid') ON DUPLICATE KEY UPDATE amount=VALUES(amount)")->execute([$transactionId,$fine]);
            $pdo->commit(); return $fine;
        } catch(Throwable $e) { if($pdo->inTransaction()) $pdo->rollBack(); throw $e; }
    }
}
