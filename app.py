from flask import Flask, request, render_template
import csv

app = Flask(__name__, static_url_path='/static')

# Function to search the CSV file
def search_csv(case_number):
    with open('form_responses.csv', 'r') as csv_file:
        csv_reader = csv.DictReader(csv_file)
        for row in csv_reader:
            if 'Case Number' in row:
                if row['Case Number'] == case_number:
                    return row
            else:
                print(f"'Case Number' not found in row: {row}")
    return None

@app.route('/')
def index():
    return render_template('home.html')

@app.route('/search', methods=['GET'])
def search():
    case_number = request.args.get('q')
    if case_number:
        data = search_csv(case_number)
        if data:
            print(data)
            return render_template('result.html', data=data)
        else:
            return render_template('result.html', error='Case not found')
    else:
        return render_template('result.html', error='Please enter a case number')

if __name__ == '__main__':
    app.run(debug=True)