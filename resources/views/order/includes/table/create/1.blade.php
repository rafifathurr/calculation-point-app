<input type="hidden" id="customer_point">
<table class="table table-bordered datatable" id="order_item_table">
    <thead>
        <tr>
            <th width="25%">
                Menu
            </th>
            <th>
                Qty
            </th>
            <th>
                Total Point
            </th>
            <th width="5%">
                Action
            </th>
        </tr>
    </thead>
    <tbody id="table_body">
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">
                <b>Total</b>
            </td>
            <td align="right">
                <span
                    id="total_point_show">{{ !is_null(old('total_point')) ? number_format(old('total_point'), 0, ',', '.') : '0' }}</span>
                <input type="hidden" name="total_point" id="total_point"
                    value="{{ old('total_point') }}">
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
    </tfoot>
</table>
