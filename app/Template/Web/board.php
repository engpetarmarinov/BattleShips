<div class="colHeader">
	<?php foreach ($this->cols as $col): ?>
		<span class="colNum"><?= $col ?></span>
	<?php endforeach; ?>		
</div>
<div class="rowHeader">
	<?php foreach ($this->rows as $row) : ?>
		<span class="rowNum"><?= strtoupper($row) ?></span>
	<?php endforeach; ?>
</div>
<?php foreach ($this->grid as $rowNumber => $cols) : ?>
	<ul class="row">
		<?php foreach ($cols as $colNumber => $position) : ?>
			<li class="col" data-coordinates="<?= $rowNumber ?>-<?= $colNumber ?>"><?= $this->symbols[$position] ?></li>
		<?php endforeach; ?>
	</ul>
<?php endforeach; ?>
