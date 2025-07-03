<style>
    :root {
        @foreach ($cssVariables ?? [] as $cssVariableName => $cssVariableValue) --{{ $cssVariableName }}:{{ $cssVariableValue }}; @endforeach
    }
</style>
<script data-larascaff-color type="application/json">{!! json_encode($cssVariables ?? []) !!}</script>