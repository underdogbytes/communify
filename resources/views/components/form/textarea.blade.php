@props([
  'name',
  'label' => null,
  'value' => '',
  'rows' => 4,
  'maxlength' => null,
  'required' => false,
  'id' => null,
])

@php
use Illuminate\Support\Str;

$id = $id ?? Str::slug($name);
$initialValue = old($name, $value);
@endphp

<div
  x-data="{
        count: {{ strlen($initialValue) }},
        max: {{ $maxlength ?? 'null' }}
    }">
  @if($label)
  <x-input-label for="{{ $id }}" :value="$label" />
  @endif

  <textarea
    id="{{ $id }}"
    name="{{ $name }}"
    rows="{{ $rows }}"
    @if($required) required @endif
    @if($maxlength)
      maxlength="{{ $maxlength }}"
    @endif
    x-on:input="count = $event.target.value.length" {{ $attributes->merge([
      'class' => 'w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'
    ]) }}>
      {{ $initialValue }}
  </textarea>

  <div class="flex items-start justify-end mt-2">
    <x-input-error :messages="$errors->get($name)" />
    @if($maxlength)
    <span class="text-xs text-gray-400 ml-4 text-right">
      <span x-text="count"></span> / {{ $maxlength }}
    </span>
    @endif
  </div>
</div>