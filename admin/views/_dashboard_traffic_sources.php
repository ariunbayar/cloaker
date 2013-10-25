<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Manage traffic source</h2>
        <form action="<?php echo ADMIN_URL; ?>traffic_source/#traffic_source" method="POST">
            <label>Name:</label>
            <input size="38" name="name" type="text">
            <input value="Save" type="submit">
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
                <td>Options</td>
            </tr>
            <?php foreach($data['traffic_sources'] as $traffic_source): ?>
                <tr>
                    <td><?php echo $traffic_source['id'] ?></td>
                    <td><?php echo $traffic_source['name'] ?></td>
                    <td>
                        <a href="<?php echo ADMIN_URL; ?>editTrafficSource/<?php echo $traffic_source['id']; ?>/">Edit</a>
                        &nbsp;&nbsp;&nbsp;
                        <a href="<?php echo ADMIN_URL; ?>deleteTrafficSource/<?php echo $traffic_source['id']; ?>/">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>
