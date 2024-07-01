<input type="hidden" id="customer_point" value="{{ $order->customer->point + $order->total_point }}">
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
        @foreach ($order->orderItem as $order_item)
            <tr id="menu_{{ $order_item->menu->id }}">
                <td>
                    {{ $order_item->menu->name }}
                    <input type="hidden" name="order_item[{{ $order_item->menu->id }}][menu]"
                        value="{{ $order_item->menu->id }}">
                    <input type="hidden" name="order_item[{{ $order_item->menu->id }}][promo_point]"
                        value="{{ $order_item->promoPoint->id }}">
                </td>
                <td>
                    <input type="number" class="form-control text-center"
                        name="order_item[{{ $order_item->menu->id }}][qty]"
                        id="order_item_qty_{{ $order_item->menu->id }}" value="{{ $order_item->qty }}" readonly
                        required>
                </td>
                <td>
                    <span
                        id='point_show_{{ $order_item->menu->id }}'>{{ number_format($order_item->point, 0, ',', '.') }}</span>
                    <input type="hidden" name="order_item[{{ $order_item->menu->id }}][point]"
                        id="order_item_point_{{ $order_item->menu->id }}" value="{{ $order_item->point }}">
                </td>
                <td>
                    <button type="button" class="delete-row btn btn-sm btn-danger rounded-5" value="Delete"><i
                            class="fas fa-trash"></i></button>
                    <input type="hidden" class="form-control" name="order_item_check[]"
                        value="{{ $order_item->menu->id }}">
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">
                <b>Total</b>
            </td>
            <td align="right">
                <span id="total_point_show">{{ number_format($order->total_point, 0, ',', '.') }}</span>
                <input type="hidden" name="total_point" id="total_point" value="{{ $order->total_point }}">
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
    </tfoot>
</table>
