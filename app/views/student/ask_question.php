<?php
include __DIR__ . "/partials/header.php";
include __DIR__ . "/partials/sidebar.php";
?>

<div class="card">

    <h2>Ask New Question</h2>

    <p class="muted">
        Describe your problem clearly.
    </p>

    <br>

    <?php if (!empty($message)): ?>

        <div class="alert alert-success">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <label>Course</label>

        <select
            name="course_id"
            class="form-control"
            required
        >

            <option value="">
                Select Course
            </option>

            <?php while(
                $course =
                $courses->fetch_assoc()
            ): ?>

                <option
                    value="<?= $course["id"] ?>"
                >

                    <?= htmlspecialchars(
                        $course["title"]
                    ) ?>

                </option>

            <?php endwhile; ?>

        </select>

        <label>Question Title</label>

        <input
            type="text"
            name="title"
            class="form-control"
            required
        >

        <label>Description</label>

        <textarea
            name="body"
            class="form-control"
            rows="7"
            required
        ></textarea>

        <button
            type="submit"
            class="btn btn-success"
        >
            Post Question
        </button>

    </form>

</div>

<?php
include __DIR__ . "/partials/footer.php";
?>