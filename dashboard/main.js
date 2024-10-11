// Set width and height for the map
let width = 400;
let height = 700;

// D3 Projection
let projection = d3
  .geoAlbers()
  .center([100.0, 13.5])
  .rotate([0, 24])
  .parallels([5, 21])
  .scale(1200 * 2)
  .translate([-100, 200]);

// Define path generator for the GeoJSON data
let path = d3.geoPath().projection(projection);

// Define color scale based on the number of interns
let color = d3
  .scaleThreshold()
  .domain([0]) // 0 interns or more than 0
  .range(["gainsboro", "#ffae01"]); // สีเทาสำหรับไม่มีข้อมูล interns, สีเหลืองสำหรับมี interns

// Create the SVG element for the map
let svg = d3
  .select("#result")
  .append("svg")
  .attr("class", "map")
  .attr("width", width)
  .attr("height", height);

// Append a group element to hold the map paths
let g = svg.append("g");

// Append tooltip div for mouseover effect
let tooltip = d3
  .select("body")
  .append("div")
  .attr("class", "tooltip")
  .style("opacity", 0);

let geo; // Variable to hold GeoJSON data

// Define zoom behavior
let zoom = d3
  .zoom()
  .scaleExtent([1, 8]) // Set zoom limits
  .on("zoom", (event) => {
    g.attr("transform", event.transform); // Apply zoom and pan transform
  });

// Apply zoom behavior to the SVG
svg.call(zoom);

// Function to update the map with intern data
let updateProvincesWithInterns = (internData) => {
  internData.forEach((internInfo) => {
    // Match province names from GeoJSON (Thai names) with the fetched intern data (also in Thai)
    geo.features.forEach((feature) => {
      if (feature.properties.NAME_1 === internInfo.province_en) {
        feature.properties.num_interns = internInfo.num_interns; // Add the number of interns
        feature.properties.interns = internInfo.interns; // Add the intern details
        feature.properties.intern_ids = internInfo.intern_ids; // Add intern_ids
        feature.properties.province_th = internInfo.province_th; // Add the Thai province name
        feature.properties.organization_names = internInfo.organization_names; // Add organization names
      }
    });
  });

  updateMapWithInterns();
};

// Function to update the map's color based on the number of interns
let updateMapWithInterns = () => {
  g.selectAll("path").style("fill", (d) => {
    let interns = d.properties.num_interns;
    if (interns && interns > 0) {
      return "#ffae01"; // สีเหลืองสำหรับจังหวัดที่มี interns
    } else {
      return "gainsboro"; // สีเทาสำหรับจังหวัดที่ไม่มี interns
    }
  });
};

// Function to display intern details in the table
let displayInternDetails = (interns, provinceName, internIds) => {
  // Clear any previous table data
  let tableBody = d3.select("#intern-table tbody");
  tableBody.html(""); // Clear existing rows

  // If there are no interns, show a message
  if (!interns || interns.length === 0) {
    tableBody
      .append("tr")
      .append("td")
      .attr("colspan", 6)
      .attr(
        "class",
        "text-center py-4 text-lg font-bold text-gray-700 bg-gray-100"
      )
      .text(`ไม่มีนิสิตที่เข้าร่วมฝึกสหกิจศึกษาใน จังหวัด${provinceName}`);
    return;
  }

  // Split intern IDs, assuming they are separated by "|"
  let idsArray = internIds ? internIds.split("|") : [];

  // Add rows to the table
  interns.forEach((intern, index) => {
    let row = tableBody.append("tr").attr("class", "hover:bg-gray-100");

    // Add the student's image to the first cell with a fallback if the image doesn't exist
    row
      .append("td")
      .attr("class", "mx-8")
      .append("img")
      .attr("src", `../assets/img/student/${intern.student_id}.jpg`) // Replace with a known image file to test
      .attr("alt", `Image of ${intern.student_id}`) // Alt text for accessibility
      .attr("class", "w-14 object-cover"); // Tailwind classes for image styling (circular, responsive)

    // Add the student's ID, name, course, and year

    row
      .append("td")
      .attr(
        "class",
        "px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-b border-gray-200"
      )
      .text(intern.student_id); // Display intern's studentid

    row
      .append("td")
      .attr(
        "class",
        "px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-b border-gray-200"
      )
      .text(intern.name); // Display intern's full name

    row
      .append("td")
      .attr(
        "class",
        "px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-b border-gray-200"
      )
      .text(intern.course); // Display intern's course

    row
      .append("td")
      .attr(
        "class",
        "px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-b border-gray-200"
      )
      .text(intern.year); // Display intern's year
    row
      .append("td")
      .attr(
        "class",
        "px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-b border-gray-200"
      )
      .text(intern.organization_name); // Display intern's organization name
  });
};

// Load the GeoJSON data for the map of Thailand
d3.json("data/thailand-new.json").then((json) => {
  geo = json;

  // Create SVG paths for each province in the GeoJSON
  g.selectAll("path")
    .data(geo.features)
    .enter()
    .append("path")
    .attr("d", path)
    .style("stroke", "#fff")
    .style("stroke-width", "1")
    .style("fill", (d) => {
      let interns = d.properties.num_interns;
      return interns && interns > 0 ? "#ffae01" : "gainsboro"; // สีเหลืองถ้ามี interns
    })
    .on("mouseover", (event, d) => {
      // Change opacity on hover
      d3.select(event.currentTarget).style("fill-opacity", 0.7); // ลดความเข้มสีเมื่อ hover

      // Show card with province name and number of interns
      const card = d3.select("body")
        .append("div")
        .attr("class", "province-card absolute bg-white shadow-lg p-4 rounded-lg border border-gray-200")
        .style("left", event.pageX + "px")
        .style("top", event.pageY - 30 + "px")
        .style("opacity", 0)
        .style("transform", "translate(-50%, -100%)") // To center the card above the cursor
        .style("pointer-events", "none") // Disable pointer events for smooth hover behavior
        .html(`
          <div class="text-lg font-semibold text-gray-800">${d.properties.province_th || d.properties.NAME_1}</div>
          <div class="text-sm text-gray-600">จำนวนนิสิต: ${d.properties.num_interns || "No data"}</div>
        `);

      // Animate the card opacity for smooth appearance
      card.transition().duration(200).style("opacity", 1);
    })
    .on("mouseout", (event, d) => {
      // Reset opacity on mouseout
      d3.select(event.currentTarget).style("fill-opacity", 1); // คืนค่าความเข้มสีเดิม

      // Hide and remove the card
      d3.selectAll(".province-card").transition().duration(500).style("opacity", 0).remove();
    })
    .on("click", (event, d) => {
      console.log("Adding map-left class");
      // Move map to left and show table
      document.getElementById("container").classList.add("map-left");

      // Display details on click, including intern IDs
      displayInternDetails(
        d.properties.interns,
        d.properties.province_th || d.properties.NAME_1,
        d.properties.intern_ids
      );
    });

  // Fetch intern data from PHP and update the map
  fetch("fetchIntern.php")
    .then((response) => response.json())
    .then((data) => {
      updateProvincesWithInterns(data); // Update the map with intern data
    })
    .catch((error) => console.error("Error fetching intern data:", error));
});
