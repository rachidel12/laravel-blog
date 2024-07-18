@switch($number)
    @case(2)
        Nombre égale à 2
        @break

    @case(15)
        Nombre égal à 15
        @break

    @default
        Nombre ni égal à 2 ni à 15
@endswitch

{{-- @isset($fruits)
	{{ count($fruits) }}
@endif --}}

{{-- @php
	echo rand(1, 15);
@endphp --}}

{{-- @forelse($fruits as $fruit)
	<p>{{ $fruit }}</p>
@empty
	Aucun fruit
@endforelse --}}

{{-- @foreach($fruits as $fruit)
	<p>{{ $fruit }}</p>
@endforeach --}}

{{-- @unless($number == 5)
	Nombre est différent de 5
@endunless --}}

{{-- @for($i = 0; $i <= 5; $i++)
	<p>Nombre égal à {{ $i }}</p>
@endfor --}}

{{-- @if($number < 5)
	Inférieur à 5
@elseif($number == 5)
	Egal à 5
@else
	Supérieur à 5
@endif --}}