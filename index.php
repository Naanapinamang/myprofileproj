<?php 
include 'includes/functions.php'; 

// For this example, we assume we are viewing the profile of User ID 1
$profile_id = 1; 

// Fetch Basic Info
$res_basic = $conn->query("SELECT * FROM profile_basics WHERE user_id = $profile_id");
$profile = $res_basic->fetch_assoc();

// Fetch all records (Experience, Education, etc.)
$res_records = $conn->query("SELECT * FROM profile_records WHERE user_id = $profile_id ORDER BY id DESC");
$records = [];
while($row = $res_records->fetch_assoc()){
    $records[$row['category']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $profile['full_name'] ?> - Professional Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* [KEEP YOUR ORIGINAL CSS HERE - It works perfectly] */
        /* Note: Make sure to include all the CSS from your original document */
        <?php include 'style.css'; // Or paste your CSS block here ?>
    </style>
</head>
<body>

<div class="container">
    <div class="left-column">
        <div class="sticky-wrapper">
            <?php 
                $img_path = !empty($profile['profile_img']) ? "uploads/".$profile['profile_img'] : "Images/profile.jpeg"; 
            ?>
            <img src="<?= $img_path ?>" alt="<?= $profile['full_name'] ?>" onerror="this.src='https://via.placeholder.com/180/ffffff/6a1b9a?text=JN'">
            
            <nav class="nav-menu">
                <button class="nav-btn active" onclick="showSection('summary')"><i class="fas fa-user"></i> Summary</button>
                <button class="nav-btn" onclick="showSection('experience')"><i class="fas fa-briefcase"></i> Experience</button>
                <button class="nav-btn" onclick="showSection('education')"><i class="fas fa-graduation-cap"></i> Education</button>
                <button class="nav-btn" onclick="showSection('certs')"><i class="fas fa-certificate"></i> Certifications</button>
                <button class="nav-btn" onclick="showSection('skills')"><i class="fas fa-tools"></i> Skills</button>
            </nav>
        </div>
    </div>

    <div class="right-column">
        <div class="profile-header">
            <h1><?= $profile['full_name'] ?></h1>
            <p><?= $profile['job_headline'] ?></p>
        </div>

        <div id="summary" class="content-section active">
            <h3><i class="fas fa-star"></i> About Me</h3>
            <p><?= nl2br($profile['about_text']) ?></p>
        </div>

        <div id="experience" class="content-section">
            <h3><i class="fas fa-history"></i> Professional Experience</h3>
            <?php if(isset($records['experience'])): foreach($records['experience'] as $exp): ?>
                <div class="info-card">
                    <h4><?= $exp['title'] ?> | <?= $exp['subtitle'] ?></h4>
                    <span><?= $exp['duration'] ?></span>
                    <p><?= nl2br($exp['description'] ?? '') ?></p>
                </div>
            <?php endforeach; endif; ?>
        </div>

        <div id="education" class="content-section">
            <h3><i class="fas fa-university"></i> Education</h3>
            <?php if(isset($records['education'])): foreach($records['education'] as $edu): ?>
                <div class="info-card">
                    <h4><?= $edu['title'] ?></h4>
                    <span><?= $edu['subtitle'] ?> | <?= $edu['duration'] ?></span>
                </div>
            <?php endforeach; endif; ?>
        </div>

        <div id="certs" class="content-section">
            <h3><i class="fas fa-award"></i> Certifications</h3>
            <?php if(isset($records['certs'])): foreach($records['certs'] as $cert): ?>
                <div class="info-card">
                    <h4><?= $cert['title'] ?></h4>
                    <p><?= $cert['subtitle'] ?> (<?= $cert['duration'] ?>)</p>
                </div>
            <?php endforeach; endif; ?>
        </div>

        <div id="skills" class="content-section">
            <h3><i class="fas fa-code"></i> Technical Toolbox</h3>
            <?php if(isset($records['skills'])): foreach($records['skills'] as $skill): ?>
                <div class="info-card">
                    <strong><?= $skill['title'] ?>:</strong> <?= $skill['description'] ?>
                </div>
            <?php endforeach; endif; ?>
        </div>

        <div class="social-links">
            <a href="#"><i class="fab fa-linkedin"></i></a>
            <a href="#"><i class="fab fa-github"></i></a>
            <a href="admin.php"><i class="fas fa-lock"></i></a> </div>
    </div>
</div>

<script>
    function showSection(sectionId) {
        document.querySelectorAll('.content-section').forEach(sec => sec.classList.remove('active'));
        document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById(sectionId).classList.add('active');
        event.currentTarget.classList.add('active');
    }
</script>
</body>
</html>
