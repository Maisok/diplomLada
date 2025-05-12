<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <x-header/>
    <div class="bg-blue-100 py-8">
        <div class="relative text-center">
            <img src="{{ asset('images/your-image-url.jpg') }}" alt="Background Image" class="w-full h-64 object-cover">
            <button class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white py-1 px-4 rounded-full shadow-lg">Create Appointment</button>
        </div>
    
        <div class="container mx-auto mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-32">
            <div class="text-center">
                <img src="{{ asset($staff->image) }}" alt="{{ $staff->first_name }} {{ $staff->last_name }}" class="w-full h-48 object-cover">
                <p class="mt-2">{{ $staff->first_name }} {{ $staff->last_name }}</p>
                <p class="mt-2">{{ $staff->position }}</p>
            </div>

            <div class="col-span-full">
                <h2 class="text-2xl font-bold">Service</h2>
                <div class="text-center">
                    <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="h-48 object-cover">
                    <p class="mt-2">{{ $service->name }}</p>
                </div>
            </div>

            <div class="col-span-full">
                <h2 class="text-2xl font-bold">Create Appointment</h2>
                <form action="{{ route('appointment.store', $staff) }}" method="POST" onsubmit="return validateForm()">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" class="form-control" required id="appointmentDate">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>


    <script>
        function validateForm() {
            const dateInput = document.getElementById('appointmentDate');
            const selectedDate = new Date(dateInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Устанавливаем время на начало дня

            if (selectedDate < today) {
                alert('Please select a date that is not in the past.');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>