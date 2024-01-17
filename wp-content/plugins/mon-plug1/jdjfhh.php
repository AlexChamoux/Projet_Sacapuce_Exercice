<tr>
    <th scope="row"><label for="statut">Statut</label></th>
    <td>
        <select name="statut" id="statut" class="regular-text" required>
            <option value="">Choisissez un statut</option>'; ?>
<?php foreach ($resultsSta as $sta) {
    echo '<option value="' . esc_html($sta->id_category) . '">' . esc_html($sta->name_category) . '</option>';
}?>
<?php echo ' </select>
    </td>
</tr>