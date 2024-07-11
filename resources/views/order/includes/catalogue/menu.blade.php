<div class="row g-4 justify-content-start mt-5">
    @php
        $index = 0;
    @endphp
    @foreach ($menus as $menu)
        @if ($type == 0)
            <div class="col-md-6 col-lg-4 col-xl-4 py-3 rounded-5">
                <div class="rounded position-relative shadow">
                    <div class="p-5 h-100 rounded-top-5 border-bottom border-bottom-secondary"
                        style="background-image:url('{{ '../../' . $menu->attachment }}');background-repeat:no-repeat;background-size:cover;background-position:center;min-height: 200px;">
                    </div>
                    <div class="p-3 rounded-bottom">
                        <h5 class="mt-3">
                            <b>{{ $menu->name }}</b>
                        </h5>
                        <div class="mt-3">
                            <p class="text-right">Rp.
                                {{ number_format($menu->price, 0, ',', '.') }},-</p>
                        </div>
                        <div class="my-3">
                            <button type="button" onclick="addMenu({{ $menu->id }})"
                                class="btn btn-sm btn-block btn-primary rounded">
                                <i class="fas fa-plus mr-1"></i>
                                Tambah Menu
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($type == 2)
            @if (!is_null($menu->price))
                <div class="col-md-6 col-lg-4 col-xl-4 py-3 rounded-5">
                    <div class="rounded position-relative shadow">
                        <div class="p-5 h-100 rounded-top-5 border-bottom border-bottom-secondary"
                            style="background-image:url('{{ '../../' . $menu->attachment }}');background-repeat:no-repeat;background-size:cover;background-position:center;min-height: 200px;">
                        </div>
                        <div class="p-3 rounded-bottom">
                            <h5 class="mt-3">
                                <b>{{ $menu->name }}</b>
                            </h5>
                            <div class="mt-3">
                                <p class="text-right">Rp.
                                    {{ number_format($menu->price, 0, ',', '.') }},-</p>
                            </div>
                            <div class="my-3">
                                <button type="button" onclick="addMenu({{ $menu->id }})"
                                    class="btn btn-sm btn-block btn-primary rounded">
                                    <i class="fas fa-plus mr-1"></i>
                                    Tambah Menu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (!is_null($menu->promoPoint))
                <div class="col-md-6 col-lg-4 col-xl-4 py-3 rounded-5">
                    <div class="rounded position-relative shadow">
                        <div class="p-5 h-100 rounded-top-5 border-bottom border-bottom-secondary"
                            style="background-image:url('{{ '../../' . $menu->attachment }}');background-repeat:no-repeat;background-size:cover;background-position:center;min-height: 200px;">
                        </div>
                        <div class="p-3 rounded-bottom">
                            <h5 class="mt-3">
                                <b>{{ $menu->name }}</b>
                            </h5>
                            <div class="mt-3">
                                <p class="text-right">
                                    {{ $menu->promoPoint->point }} Point</p>
                                <input type="hidden" name="point_product[]" value="{{ $menu->promoPoint->point }}">
                            </div>
                            <div class="my-3">
                                <button type="button"
                                    onclick="addMenu({{ $menu->id }},{{ $menu->promoPoint->id }})"
                                    class="btn btn-sm btn-block btn-primary btn-add-{{ $index }} rounded">
                                    <i class="fas fa-plus mr-1"></i>
                                    Tambah Menu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $index++;
                @endphp
            @endif
        @endif
    @endforeach
</div>
<div class="row justify-content-center">
    <div class="mt-3">
        {{ $menus->links() }}
    </div>
</div>
<script>
    $('.pagination a').on('click', function(event) {
        event.preventDefault();

        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        let page = $(this).attr('href').split('page=')[1];
        catalogue(page);
    });
</script>
