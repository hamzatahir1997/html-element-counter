<h1>HTML Element Counter</h1>

<p>HTML Element Counter is a web application that allows users to input a URL and an HTML element name. The application fetches the content of the URL, counts the occurrences of the specified element, and displays the result along with some general statistics.</p>

<h2>Features</h2>
<ul>
    <li>Input any valid URL and HTML element name.</li>
    <li>Fetches the content of the URL in real-time.</li>
    <li>Counts and displays the occurrences of the specified HTML element.</li>
    <li>Provides general statistics about the requests made by users.</li>
    <li>Caches results to avoid redundant requests within a short time frame.</li>
</ul>

<h2>Setup and Installation</h2>
<ol>
    <li><strong>Clone the Repository</strong>:
        <pre><code>git clone https://github.com/hamzatahir1997/html-element-counter.git</code></pre>
    </li>
    <li><strong>Database Setup</strong>:
        <ul>
            <li>Modify the <code>config.php</code> file to match your database credentials.</li>
            <li>Run the <code>install_db.php</code> script to set up the necessary database and tables.</li>
        </ul>
    </li>
    <li><strong>Run the Application</strong>:
        <ul>
            <li>Host the files on a local or remote server.</li>
            <li>Access <code>index.php</code> through a web browser to use the application.</li>
        </ul>
    </li>
</ol>

<h2>Usage</h2>
<ol>
    <li>Open the application in a web browser.</li>
    <li>Enter a valid URL in the "URL" input field.</li>
    <li>Enter the name of an HTML element (e.g., <code>div</code>, <code>img</code>, <code>a</code>) in the "Element" input field.</li>
    <li>Click the "Check" button.</li>
    <li>The application will display the count of the specified element in the provided URL along with some general statistics.</li>
</ol>

<h2>Contributing</h2>
<p>Contributions are welcome! Please fork the repository and create a pull request with your changes.</p>
