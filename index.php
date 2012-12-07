<?php
/**
 * ilo.ve.it URL Shortner script
 * 
 * @author Matthew Dunham <me@dunhamzzz.com>
 */
require_once('config.php');
if (isset($_POST['s']) && !empty($_POST['u'])) {
    $errors = '';
    if (filter_var($_POST['u'], FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED)) {
        if (empty($_POST['t'])) {
            $tag = generateTag($_POST['t']);
        } else {
            // Check tag is valid
            if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['t'])) {
                // Check if tag exists
                $stmt = $db->prepare("SELECT `id` FROM `urls` WHERE `tag` = ?");
                $stmt->bind_param("s", $_POST['t']);
                $stmt->execute();
                $stmt->bind_result($id);
                $stmt->fetch();
                if ($id != false) {
                    $errors = 'This tag has already been taken! Plese try another.';
                } else {
                    $tag = $_POST['t'];
                }
            } else {
                $errors = 'Tag may contain letters and numbers only.';
            }
        }

        // Insert it.
        if (empty($errors)) {
            $stmt = $db->prepare("INSERT INTO `urls` (`ip`, `url`, `tag`, `created`) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $_SERVER['REMOTE_ADDR'], $_POST['u'], $tag);
            $stmt->execute();
            header("Location: index.php?success=true&tag=" . $tag);
        }
    } else {
        $errors = 'Invalid URL, include http://';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>I Love It! URL Shortener</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css"/>
        <meta name="keywords" content="URL Shortener, short urls, trim urls, bitly, twitter urls, urls, url, shorten" />
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-23577209-6']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        </script>
    </head>
    <body>
        <div id="header">
            <h1>ilo.ve.it <span class="heart">&hearts;</span> URL Shortener</h1>
        </div>
        <form method="post" action="index.php">
            <?php if (!empty($errors)): ?>
                <p id="error"><?php echo $errors; ?></p>
            <?php else: ?>
                <?php if (isset($_GET['success']) && isset($_GET['tag'])): ?>
                    <p><b>Your URL has been shortened!</b></p>
                    <p>Here is your short URL: <a href="<?php echo ROOT_URL . htmlspecialchars($tag); ?>"
                                                  target="_blank" rel="nofollow"><?php echo ROOT_URL . $tag; ?></a></p>
                    <?php else: ?>
                    <p>Share a link and let them know you love it!</p>
                <?php endif; ?>
            <?php endif; ?>
            <b id="l">Long URL</b><br />
            <input id="u" name="u" type="text" placeholder="http://" value="<?php echo isset($_POST['u']) ? htmlspecialchars($_POST['u']) : ''; ?>"/>
            <input id="s" type="submit" name="submit" value="Shorten" /><br />
            <i>Tag<input id="tag" name="tag" type="text" />(optional)</i>
        </form>
        <p id="f">Copyright &copy; 2011</p>
    </body>
</html>