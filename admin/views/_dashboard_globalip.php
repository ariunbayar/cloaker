<div class="scont">
    <div class="box">
        <div class="tl"><div class="tr"></div></div>
        <h2 class="boxtitle">Global Denied IP</h2>
        <form action="<?php echo ADMIN_URL; ?>giplist/" method="POST">
            <table width="100%" cellspacing="0" cellpadding="4" border="0" class="table">
                <tbody>
                <tr>
                    <td>Global Denied IP List Edit</td><td>
                        <textarea rows="20" cols="30" name="giplist"><?php echo implode(PHP_EOL,$data['giplist']); ?></textarea></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input value="Save" type="submit"></td>
                </tr>
                </tbody>
            </table>
        </form>
        <div class="bl"><div class="br"></div></div>
    </div>
</div>
