<div class="page-header">
  <h1 class="page-title">
    {{ $slot }}
  </h1>
  @isset($rightContent)
	{{ $rightContent }}
  @endisset
</div>