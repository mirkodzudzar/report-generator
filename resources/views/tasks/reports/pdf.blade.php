<!DOCTYPE html>
<html>

<head>
    <title>{{ __('Tasks report') }}</title>
</head>

<body>
    <h1>{{ __('Tasks report for :user', ['user' => $user->name]) }}</h1>
    <p>{{ __('Total tasks: :total', ['total' => $statistics['total']]) }}</p>
    <p>{{ __('Completed tasks: :completed', ['completed' => $statistics['completed']]) }}</p>
    <p>{{ __('Incomplete tasks: :incomplete', ['incomplete' => $statistics['incomplete']]) }}</p>
    <p>{{ __('Percentage completed: :percentage', ['percentage' => $statistics['percentage']]) }}</p>
</body>

</html>
