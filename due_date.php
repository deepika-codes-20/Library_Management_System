<form method="post">
    <label>Select Book:</label>
    <select name="book_id" required>
        <option value="">--Select Book--</option>
        <?php while($row = $books->fetch_assoc()){ ?>
            <option value="<?php echo $row['id']; ?>">
                <?php echo $row['title']; ?> (Available: <?php echo $row['available_copies']; ?>)
            </option>
        <?php } ?>
    </select>

    <!-- Due Date Field -->
    <label>Due Date:</label>
    <input type="date" name="due_date" required min="<?php echo date('Y-m-d'); ?>">

    <input type="submit" name="borrow" value="Borrow Book">
</form>
