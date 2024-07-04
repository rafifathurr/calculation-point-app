<input type="hidden" id="customer_point" value="{{ old('customer_point') }}">
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
        @if (!is_null(old('order_item')))
            @foreach (old('order_item') as $menu_id => $order_item)
                <tr id="menu_{{ $menu_id }}">
                    <td>
                        {{ $order_item['name'] }}
                        <input type="hidden" name="order_item[{{ $menu_id }}][menu]" value="{{ $menu_id }}">
                    </td>
                    <td>
                        <input type="number" class="form-control text-center"
                            name="order_item[{{ $menu_id }}][qty]" id="order_item_qty_{{ $menu_id }}"
                            value="{{ $order_item['qty'] }}" readonly required>
                    </td>
                    <td>
                        <span
                            id='point_show_{{ $menu_id }}'>{{ number_format($order_item['point'], 0, ',', '.') }}</span>
                        <input type="hidden" name="order_item[{{ $menu_id }}][point]"
                            id="order_item_point_{{ $menu_id }}" value="{{ $order_item['point'] }}">
                    </td>
                    <td>
                        <button type="button" class="delete-row btn btn-sm btn-danger rounded-5" value="Delete"><i
                                class="fas fa-trash"></i></button>
                        <input type="hidden" class="form-control" name="order_item_check[]"
                            value="{{ $menu_id }}">
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">
                <b>Total</b>
            </td>
            <td align="right">
                <span
                    id="total_point_show">{{ !is_null(old('total_point')) ? number_format(old('total_point'), 0, ',', '.') : '0' }}</span>
                <input type="hidden" name="total_point" id="total_point" value="{{ old('total_point') }}">
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
    </tfoot>
</table>
