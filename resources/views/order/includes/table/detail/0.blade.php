<table class="table table-bordered datatable" id="order_item_table">
    <thead>
        <tr>
            <th>
                Menu
            </th>
            <th width="15%">
                Qty
            </th>
            <th>
                Total Harga
            </th>
            <th>
                Total Point
            </th>
        </tr>
    </thead>
    <tbody id="table_body">
        @foreach ($order->orderItem as $order_item)
            <tr>
                <td>
                    {{ $order_item->menu->name }}
                </td>
                <td>
                    {{ $order_item->qty }}
                </td>
                <td align="right">
                    {{ 'Rp. ' . number_format($order_item->price, 0, ',', '.') . ',-' }}
                </td>
                <td align="right">
                    {{ $order_item->point }}
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
                {{ 'Rp. ' . number_format($order->total_price, 0, ',', '.') . ',-' }}
            </td>
            <td align="right">
                {{ $order->total_point }}
            </td>
        </tr>
    </tfoot>
</table>
