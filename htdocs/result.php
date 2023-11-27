<?php
// Function to copy the file from Google Drive to a local directory
function copy_file_from_google_drive() {
    $source = 'G:\My Drive\WebForm\form_responses.csv';
    $destination = 'C:\Users\x279309\Desktop\Apache\Apache24\htdocs\data';

    // Copy the file
    if (!copy($source, $destination)) {
        echo "Failed to copy file.";
        return false;
    }
    return true;
}

// Function to search the CSV file
function search_csv($case_number) {

    // Using exec()
    exec('C:\Users\x279309\Desktop\Apache\Apache24\htdocs\copyfile.bat', $output, $return_var);
    if ($return_var != 0) {
        echo "Failed to execute batch file.";
    }

    // Or using shell_exec()
    $output = shell_exec('C:\Users\x279309\Desktop\Apache\Apache24\htdocs\copyfile.bat');
    if ($output === null) {
        echo "Failed to execute batch file.";
    }

    $file_path = 'C:\Users\x279309\Desktop\Apache\Apache24\htdocs\data\form_responses.csv';

    $file = fopen($file_path, 'r');
    $headers = fgetcsv($file); // Assuming the first row contains headers
    while (($row = fgetcsv($file)) !== FALSE) {
        if ($row[18] == $case_number) {
            fclose($file);
            return array_combine($headers, $row); // Combine headers with row values
        }
    }
    fclose($file);
    return null; // Return null if not found
}

// Initialize variables
$data = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $case_number = isset($_GET['q']) ? $_GET['q'] : '';

    if ($case_number) {
        $data = search_csv($case_number);
        if (!$data) {
            $error = 'Case not found';
        }
    } else {
        $error = 'There were no matches.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="/static/styles.css">
</head>
<body>
    <header>
        <h1 class="title">DATA STRATEGY USE CASES</h1>
        <h1 class="sub-title">Responses</h1>
    </header>

    <main>
        <hr>
        <section>
            <h3>Enter the use case number.</h3>
            <form action="result.php" method="get">
                <input type="text" id="search" name="q" placeholder="Search...">
                <button type="submit">Search</button>
            </form>
        </section>
        <br>
        <section class="responses">
            <div>
                <?php if ($data): ?>
                    <h2>Case Information</h2>
                    <div class="main-info">
                        <!-- Display each field -->
                        <div class="header-info">
                            <p class="questions"><strong>Case Number:</strong></p>
                            <p><?php echo htmlspecialchars($data['Case Number']); ?></p>
                        </div>
                        <div class="header-info">
                            <p class="questions"><strong>Email Address:</strong></p>
                            <p><?php echo htmlspecialchars($data['Email Address']); ?></p>
                        </div>
                        <div class="header-info">
                            <p class="questions"><strong>Timestamp:</strong></p>
                            <p><?php echo htmlspecialchars($data['Timestamp']); ?></p>
                        </div>
                        <!-- Repeat for other fields -->
                        <p class="questions"><strong>Business Unit:</strong></p>
                        <p><?php echo htmlspecialchars($data['Business Unit']); ?></p>

                        <!-- ... and so on for the other fields -->
                    </div>
                    <p class="questions"><strong>Opportunity:</strong></p>
                    <p><?php echo htmlspecialchars($data['Opportunity']); ?></p>

                    <p class="questions"><strong>Objective and Business Questions:</strong></p>
                    <p><?php echo htmlspecialchars($data['Objective and Business Questions']); ?></p>

                    <p class="questions"><strong>Use Case Owner:</strong></p>
                    <p><?php echo htmlspecialchars($data['Use Case Owner']); ?></p>

                    <p class="questions"><strong>Link To Strategic Goal:</strong></p>
                    <p><?php echo htmlspecialchars($data['Link To Strategic Goal']); ?></p>

                    <p class="questions"><strong>User and Data Customers:</strong></p>
                    <p><?php echo htmlspecialchars($data['User and Data Customers']); ?></p>

                    <p class="questions"><strong>Required Data:</strong></p>
                    <p><?php echo htmlspecialchars($data['Required Data']); ?></p>

                    <p class="questions"><strong>Data Governance:</strong></p>
                    <p><?php echo htmlspecialchars($data['Data Governance']); ?></p>

                    <p class="questions"><strong>Data Analysis and Analytics:</strong></p>
                    <p><?php echo htmlspecialchars($data['Data Analysis and Analytics']); ?></p>

                    <p class="questions"><strong>Technology:</strong></p>
                    <p><?php echo htmlspecialchars($data['Technology']); ?></p>

                    <p class="questions"><strong>Key Contributors:</strong></p>
                    <p><?php echo htmlspecialchars($data['Key Contributors']); ?></p>

                    <p class="questions"><strong>Skills & Capacity:</strong></p>
                    <p><?php echo htmlspecialchars($data['Skills & Capacity']); ?></p>

                    <p class="questions"><strong>Milestones & Timeline:</strong></p>
                    <p><?php echo htmlspecialchars($data['Milestones & Timeline']); ?></p>

                    <p class="questions"><strong>Budget Requirements:</strong></p>
                    <p><?php echo htmlspecialchars($data['Budget Requirements']); ?></p>

                    <p class="questions"><strong>Measures of Success (KPIs):</strong></p>
                    <p><?php echo htmlspecialchars($data['Measures of Success (KPIs)']); ?></p>

                    <p class="questions"><strong>Implementation and Change Management:</strong></p>
                    <p><?php echo htmlspecialchars($data['Implementation and Change Management']); ?></p>
                <?php elseif ($error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Telus</p>
    </footer>
</body>
</html>
