	<?php foreach ($this->cols as $col) : ?>
 <?= $col ?>
<?php endforeach; ?>
<?php foreach ($this->grid as $rowNumber => $cols) : ?>

<?php echo strtoupper($this->rows[$rowNumber]); ?>	<?php foreach ($cols as $colNumber => $position) : ?>
 <?= $this->symbols[$position] ?>
<?php endforeach; ?>
<?php endforeach; ?>

Shots: <?php echo $this->tries?>  <?php echo $this->msg ?>

Hints: you could type "show" or "restart".
Enter coordinates (row, col), e.g. A5 = 