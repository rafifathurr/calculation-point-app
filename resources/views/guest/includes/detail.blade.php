<div class="card-body p-5 border-top border-top-1">
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Nama</label>
        <div class="col-sm-9 col-form-label">
            {{ $customer->name }}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Nomor Telepon</label>
        <div class="col-sm-9 col-form-label">
            {{ $customer->phone }}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Alamat</label>
        <div class="col-sm-9 col-form-label">
            {{ $customer->address }}
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label">Total Point</label>
        <div class="col-sm-9 col-form-label">
            {{ $customer->point }}
        </div>
    </div>
</div>
