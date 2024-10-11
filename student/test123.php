<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country and City Info</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-4">Select Country and City</h1>

    <div class="mb-4">
        <label for="country-select" class="block mb-2">Select Country:</label>
        <select id="country-select" class="p-2 border rounded-lg w-full">
            <option value="">Select Country</option>
        </select>
    </div>

    <div>
        <label for="city-select" class="block mb-2">Select City:</label>
        <select id="city-select" class="p-2 border rounded-lg w-full">
            <option value="">Select City</option>
        </select>
    </div>

    <script>
        // Function to fetch countries
        async function fetchCountries() {
            const response = await fetch('https://restcountries.com/v3.1/all');
            const countries = await response.json();
            const countrySelect = document.getElementById('country-select');

            // Populate the country select dropdown
            countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country.cca2; // Country code
                option.textContent = country.name.common; // Country name
                countrySelect.appendChild(option);
            });
        }

        // Function to fetch cities based on the selected country
        async function fetchCities(countryCode) {
            const response = await fetch(`https://wft-geo-db.p.rapidapi.com/v1/geo/countries/${countryCode}/regions`, {
                method: 'GET',
                headers: {
                    'X-RapidAPI-Key': 'your-rapidapi-key', // Replace with your RapidAPI key
                    'X-RapidAPI-Host': 'wft-geo-db.p.rapidapi.com'
                }
            });
            const cities = await response.json();
            const citySelect = document.getElementById('city-select');

            // Clear previous cities
            citySelect.innerHTML = '<option value="">Select City</option>';

            // Populate the city select dropdown
            cities.data.forEach(city => {
                const option = document.createElement('option');
                option.value = city.id; // City id
                option.textContent = city.name; // City name
                citySelect.appendChild(option);
            });
        }

        // Fetch countries when the page loads
        fetchCountries();

        // Fetch cities when a country is selected
        document.getElementById('country-select').addEventListener('change', function () {
            const countryCode = this.value;
            if (countryCode) {
                fetchCities(countryCode);
            } else {
                document.getElementById('city-select').innerHTML = '<option value="">Select City</option>';
            }
        });
    </script>
</body>

</html>
