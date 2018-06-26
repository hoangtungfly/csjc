<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
    http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <url><loc><?= HTTP_HOST ?></loc></url>
    <?php
    if ($listCategoryNews) {
        foreach ($listCategoryNews as $key => $item) {
            ?>
            <url>
                <loc><?= HTTP_HOST . $item['link_main'] ?></loc>
                <lastmod><?= $item['created_time'] ?></lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.8</priority>
            </url>
            <?php
            $count = \common\models\news\NewsSearch::getTotalByCategoryid($item['id']);
            $totalPage = ceil($count / $limitNews);
            for ($page = 2; $page <= $totalPage; $page++) {
                $link_main = HTTP_HOST . $this->createUrl(DS . WEBNAME . '/main/category', ['alias' => $item['alias'], 'page' => $page]);
                ?>
                <url>
                    <loc><?= $link_main ?></loc>
                    <lastmod><?= $item['created_time'] ?></lastmod>
                    <changefreq>monthly</changefreq>
                    <priority>0.6</priority>
                </url>
                <?php
            }
        }
    }
    ?>
    <?php
    if ($listCategoryProduct) {
        foreach ($listCategoryProduct as $key => $item) {
            ?>
            <url>
                <loc><?= HTTP_HOST . $item['link_main'] ?></loc>
                <lastmod><?= $item['created_time'] ?></lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.8</priority>
            </url>
            <?php
        }
    }
    ?>
    <?php
    if ($listNews) {
        foreach ($listNews as $key => $item) {
            ?>
            <url>
                <loc><?= HTTP_HOST . $item['link_main'] ?></loc>
                <lastmod><?= $item['created_time'] ?></lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.8</priority>
            </url>
            <?php
        }
    }
    ?>
    <?php
    if ($listProduct) {
        foreach ($listProduct as $key => $item) {
            ?>
            <url>
                <loc><?= HTTP_HOST . $item['link_main'] ?></loc>
                <lastmod><?= $item['created_time'] ?></lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.6</priority>
            </url>
            <?php
        }
    }
    ?>
</urlset>