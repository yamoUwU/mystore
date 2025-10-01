<div class="row justify-content-center">
    <div class="col-xl-10">
        <table>
            <tbody>
            @foreach($product->specificationAttributes->where('pivot.hidden', false)->sortBy('pivot.order') as $attribute)
                <tr>
                    <td>{{ $attribute->name }}</td>
                    <td>
                        @if ($attribute->type == 'checkbox')
                            @if ($attribute->pivot->value)
                                <x-core::icon name="ti ti-check" class="text-success" style="font-size: 1.5rem;" />
                            @else
                                <x-core::icon name="ti ti-x" class="text-danger" style="font-size: 1.5rem;" />
                            @endif
                        @else
                            {{ $attribute->pivot->value }}
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
