

<form {{ $attributes->merge(['method' => 'POST', 'style' => 'display:inline;']) }}>
    @csrf
    @method('DELETE')

    <button type="submit" class="btn btn-sm btn-danger archive-form">
        {{ $slot }}
    </button>
</form>
