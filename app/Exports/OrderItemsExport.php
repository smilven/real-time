<?php
// app/Exports/OrderItemsExport.php

namespace App\Exports;

use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderItemsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Return the collection of order items for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Fetch only the necessary fields: id, order_id, product_id, quantity, price
        return OrderItem::select('id', 'order_id', 'product_id', 'quantity', 'price')->get();
    }

    /**
     * Specify the headings for the Excel file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID', 'Order ID', 'Product ID', 'Quantity', 'Price', 'Total Sales',
        ];
    }

    /**
     * Map each row of data to include the Total Sales (quantity * price).
     *
     * @param  \App\Models\OrderItem  $orderItem
     * @return array
     */
    public function map($orderItem): array
    {
        // Calculate total sales for the item (quantity * price)
        $totalSales = $orderItem->quantity * $orderItem->price;

        // Return the data in the desired format, including Total Sales
        return [
            $orderItem->id,
            $orderItem->order_id,
            $orderItem->product_id,
            $orderItem->quantity,
            $orderItem->price,
            $totalSales,  // Total Sales column
        ];
    }
}
