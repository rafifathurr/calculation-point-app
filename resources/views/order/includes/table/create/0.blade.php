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
                Total Harga
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
        {{-- @if (!is_null(old('order_item')))
            @foreach (old('order_item') as $menu_id => $order_item)
            <tr id='tr_order_item_{{ $menu_id }}'>
                <td>
                    {{ $sales_order_item['product_name'] }}
                    <input type="hidden"
                        name="sales_order_item[{{ $product_id }}][product]"
                        value="{{ $product_id }}">
                    <input type="hidden"
                        name="sales_order_item[{{ $product_id }}][product_size][{{ $product_size_id }}][product_size]"
                        value="{{ $sales_order_item['product_size'] }}">
                    <input type="hidden"
                        id="product_size_{{ $product_size_id }}"
                        name="sales_order_item[{{ $product_id }}][product_size][{{ $product_size_id }}][product_name]"
                        value="{{ $sales_order_item['product_name'] }}">
                </td>
                <td>
                    <input type="number"
                        class="form-control text-center"
                        id="qty_{{ $product_size_id }}"
                        max = '{{ $sales_order_item['stock'] }}'
                        min='1'
                        value="{{ $sales_order_item['qty'] }}"
                        name="sales_order_item[{{ $product_id }}][product_size][{{ $product_size_id }}][qty]">
                    <input type='hidden'
                        name = 'sales_order_item[{{ $product_id }}][product_size][{{ $product_size_id }}][stock]'
                        value = '{{ $sales_order_item['stock'] }}'>
                    <input type="hidden"
                        id="capital_price_{{ $product_size_id }}"
                        name="sales_order_item[{{ $product_id }}][product_size][{{ $product_size_id }}][capital_price]"
                        value="{{ $sales_order_item['capital_price'] }}">
                    <input type="hidden"
                        id="sell_price_{{ $product_size_id }}"
                        name="sales_order_item[{{ $product_id }}][product_size][{{ $product_size_id }}][sell_price]"
                        value="{{ $sales_order_item['sell_price'] }}">
                    <input type="hidden"
                        id="discount_{{ $product_size_id }}"
                        name="sales_order_item[{{ $product_id }}][product_size][{{ $product_size_id }}][discount_price]"
                        value="{{ $sales_order_item['discount_price'] }}">
                </td>
                <td align="right">
                    Rp. <span
                        id="price_show_{{ $product_size_id }}">{{ number_format($sales_order_item['total_sell_price'], 0, ',', '.') }}</span>
                    <input type="hidden"
                        id="total_sell_price_{{ $product_size_id }}"
                        name="sales_order_item[{{ $product_id }}][product_size][{{ $product_size_id }}][total_sell_price]"
                        value="{{ $sales_order_item['total_sell_price'] }}">
                    <input type="hidden"
                        id="total_profit_price_{{ $product_size_id }}"
                        name="sales_order_item[{{ $product_id }}][product_size][{{ $product_size_id }}][total_profit_price]"
                        value="{{ $sales_order_item['total_profit_price'] }}">
                </td>
                <td align="center">
                    <button type="button"
                        class="delete-row btn btn-sm btn-danger"
                        title="Delete">Del</button>
                    <input type="hidden"
                        name="sales_order_item_check[]"
                        value="{{ $product_size_id }}">
                </td>
            </tr>
            @endforeach
        @endif --}}
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">
                <b>Total</b>
            </td>
            <td align="right">
                <span
                    id="total_price_show">{{ !is_null(old('total_price')) ? 'Rp. ' . number_format(old('total_price'), 0, ',', '.') : 'Rp. 0,-' }}</span>
                <input type="hidden" name="total_price" id="total_price" value="{{ old('total_price') }}">
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
