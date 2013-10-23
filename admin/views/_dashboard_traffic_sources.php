<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Manage traffic source</h2>
        <form action="" method="POST">
            <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                <tbody>
                <tr>
                    <td>Name</td><td><input size="38" name="name" type="text">
                    <input value="Create" type="submit"></td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Traffic Sources</h2>
        <table width="100%" cellspacing="0" cellpadding="4" border="1" class="table">
            <tbody>
            <tr>
                <td>ID</td>
                <td>Name</td>
            </tr>
            <?php foreach($data['traffics'] as $traffic): ?>
                <tr>
                    <td><?php echo $traffic['id'] ?></td>
                    <td><?php echo $traffic['name'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>
