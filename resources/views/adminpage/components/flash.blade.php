@if (session('success'))
  <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
    {{ session('success') }}
  </div>
@endif

@if (session('error'))
  <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
    {{ session('error') }}
  </div>
@endif

@if ($errors->any())
  <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
    <div class="font-semibold">Please fix the errors:</div>
    <ul class="mt-2 list-disc pl-5">
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif