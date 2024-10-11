<?php include 'header.php'; ?>
<?php include 'config/config.php'; ?>

<?php
// Mendapatkan query pencarian dan kategori
$query = isset($_GET['query']) ? $_GET['query'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

try {
    // Melakukan pencarian di database
    $sql = "SELECT * FROM articles WHERE (title LIKE '%$query%' OR content LIKE '%$query%')";
    if ($category) {
        $sql .= " AND category = '$category'";
    }
    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception("Query failed");
    }
} catch (Exception $e) {
    $result = null;
}
?>

<div class="container mx-auto mt-8 mb-16">
    <h1 class="text-3xl font-bold mb-6 text-center">Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>
    <?php if ($result && $result->num_rows > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while($row = $result->fetch_assoc()): ?>
                <div>
                    <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($row['title']); ?></h2>
                    <p class="text-gray-700 mb-4"><?php echo htmlspecialchars(substr($row['content'], 0, 100)); ?>...</p>
                    <a href="article.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline">Read more</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center">
            <h2 class="text-2xl font-bold mb-2">No Results Found</h2>
            <p class="text-gray-700 mb-4">We couldn't find any content matching your search query<?php echo $category ? " in the category '$category'" : ''; ?>. Please try again with different keywords.</p>
        </div>
    <?php endif; ?>
</div>

<?php
// Menutup koneksi
$conn->close();
?>

<?php include 'footer.php'; ?>