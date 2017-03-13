<?php
include('._._inc_header._._.php');

$getVerifiedCharCount = $odb->prepare("SELECT * FROM `characters` WHERE `userid` = ? AND `status` = ?");
$getVerifiedCharCount->BindValue(1, $userData['id']);
$getVerifiedCharCount->BindValue(2, "1");
$getVerifiedCharCount->execute();

if(isset($_GET['id'])){
    $validated = false;
    if(is_numeric($_GET['id'])){
        $validateAuctionID = $odb->prepare("SELECT * FROM `auctions` WHERE `id` = ?");
        $validateAuctionID->BindValue(1, $_GET['id']);
        $validateAuctionID->execute();

        if($validateAuctionID->rowCount() == 1){
            $validated = true;
        }else{
            $TibiaMarket->redirect("index");
        }
    }else{
        $TibiaMarket->redirect("index");
    }
}else{
    $TibiaMarket->redirect("index");
}

$auctionFetcher = $validateAuctionID->fetch(PDO::FETCH_ASSOC);
if($auctionFetcher['status'] == "2"){
	$pending = true;
}
$getLastBid = $odb->prepare("SELECT * FROM `bids` WHERE `auctionid` = ? ORDER BY `id` DESC LIMIT 1");
$getLastBid->BindValue(1, $auctionFetcher['id']);
$getLastBid->execute();
$getLastBidF = $getLastBid->fetch(PDO::FETCH_ASSOC);
if(empty($getLastBidF['bid'])){
    $currentBid = "0";
}else{
    $currentBid = $getLastBidF['bid'];
}

if(isset($_POST['mybidding'])){
    if(is_numeric($_POST['mybidding'])){
        if($auctionFetcher['status'] == "0" || $auctionFetcher['status'] == "99"){
            
            $myBid = inputSanitize($_POST['mybidding']);
            $charFromBid = inputSanitize($_POST['charFromBid']);

            if($auctionFetcher['userid'] == $userData['id']){
                $errors[] = "You cannot bid on your own auctions.";
            }

            if(!is_numeric($charFromBid)){
                $errors[] = "Character not found.";
            }

            if($myBid <= $auctionFetcher['minbid']){
                $errors[] = "Your bid is lower than the minimum bid of the auction.";
            }else{
                if($myBid <= $currentBid){
                    $errors[] = "Your bid is lower than the current bid.";
                }
            }

            if($getVerifiedCharCount->rowCount() == 0){
                $errors[] = "You do not have a verified character.";
            }

            if($TibiaMarket->characterTable($charFromBid, "userid") != $userData['id']){
                $errors[] = "You must choose a character in order to make a bid.";
            }

            $tenTimesMax = ($currentBid * 10) + 2;

            if($myBid > $tenTimesMax && $currentBid != 0){
                $errors[] = "You cannot bid more than " . number_format($tenTimesMax) . " for the time being.";
            }

            if(empty($errors)){
                //add my bidf

                $addMyBid = $odb->prepare("INSERT INTO `bids` (`userid`, `auctionid`, `bid`, `charid`, `date`) VALUES(?, ?, ?, ?, ?)");
                $addMyBid->BindValue(1, $userData['id']);
                $addMyBid->BindValue(2, $auctionFetcher['id']);
                $addMyBid->BindValue(3, $myBid);
                $addMyBid->BindValue(4, $charFromBid);
                $addMyBid->BindValue(5, $TibiaMarket->dateTime());
                $addMyBid->execute();

                if(empty($getLastBidF['userid'])){

                }else{
                    $message = "You have been outbidded in an auction!";
                    $link = $siteURL . "auction/" . $auctionFetcher['id'];
                    $insertNotif = $odb->prepare("INSERT INTO `notifications` (`userid`, `message`, `link`, `date`) VALUES(?, ?, ?, ?)");
                    $insertNotif->BindValue(1, $getLastBidF['userid']);
                    $insertNotif->BindValue(2, $message);
                    $insertNotif->BindValue(3, $link);
                    $insertNotif->BindValue(4, $TibiaMarket->dateTime());
                    $insertNotif->execute();
                }

                $TibiaMarket->redirect("{$siteURL}auction/" . $auctionFetcher['id']);
            }

        }else{
            $errors[] = "Auction has already ended";
        }
    }else{
        $errors[] = "Your bid must be positive";
    }
}
?>

<div id="content">
    <div id="content-header">
        <h1>Auction Information</h1>
    </div>
    <div id="breadcrumb">
        <a href="#" title="Go to Home" class="tip-bottom"><i class="fa fa-home"></i> Home</a>
        <a href="#" class="current">Auction Information</a>
    </div>
    <div class="row">
	<?php if(empty($pending)){ ?>
        <div class="col-sm-12">
            <?php
                if(isset($errors)){
                    if(!empty($errors)){
                        echo '<div class="alert alert-danger">';
                        echo '<button type="button" class="close" data-dismiss="alert">×</button>';
            
                            foreach($errors as $error){
                                echo ''.$error.'<br />';
                            }
            
                        echo '</div>';
                    }
                }
            ?>
        </div>
        <div class="col-sm-6">
            <div class="tabbable inline">
                <ul class="nav nav-tabs tab-bricky" id="myTab">
                    <li class="active">
                        <a data-toggle="tab" href="#panel_tab2_example1">
                            Information
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#panel_tab2_example2">
                            Bidding History
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="panel_tab2_example1" class="tab-pane in active">
        
                    <table class="table table-bordered table-striped table-hover data-table dataTable" id="DataTables_Table_1">
                        <tbody>
                        
                            <tr class="gradeX odd">
                                <td class="" style="width: 100px;">
                                    <center>Item</center>
                                </td>
                                <td class=" ">
                                    <center><?php _echo($TibiaMarket->itemTable($auctionFetcher['item'], "name"));?></center>
                                </td>
                            </tr>
                        
                            <tr class="gradeX odd">
                                <td class="">
                                    <center>World</center>
                                </td>
                                <td class=" ">
                                    <center><?php _echo($TibiaMarket->worldTable($auctionFetcher['world'], "name"));?></center>
                                </td>
                            </tr>
                        
                            <tr class="gradeX odd">
                                <td class="">
                                    <center>Character</center>
                                </td>
                                <td class=" ">
                                    <center><?php _echo($TibiaMarket->characterTable($auctionFetcher['charid'], "name"));?></center>
                                </td>
                            </tr>
                        
                            <tr class="gradeX odd">
                                <td class="">
                                    <center>Length</center>
                                </td>
                                <td class=" ">
                                    <center><?php _echo($auctionFetcher['length']);?></center>
                                </td>
                            </tr>
        
                            <tr class="gradeX odd">
                                <td class="">
                                    <center>Minimum Bid</center>
                                </td>
                                <td class=" ">
                                    <center><?php _echo($auctionFetcher['minbid']);?></center>
                                </td>
                            </tr>
                        
                            <tr class="gradeX odd">
                                <td class="">
                                    <center>Current Bid</center>
                                </td>
                                <td class=" ">
                                    <center>
                                        <?php
                                            if($currentBid == "0"){
                                                _echo("0");
                                            }else{
                                                _echo($currentBid . " by " . $TibiaMarket->characterTable($getLastBidF['charid'], "name")); //$TibiaMarket->userTable($['userid'], "username"));
                                            }
                                        ?>
                                    </center>
                                </td>
                            </tr>

                            <tr class="gradeX odd">
                                <td class="">
                                    <center>Bidding Ends</center>
                                </td>
                                <td class=" ">
                                    <center><?php _echo(date('Y-m-d H:i', $TibiaMarket->auctionTable($auctionFetcher['id'], "expire")));?></center>
                                </td>
                            </tr>

                            <tr class="gradeX odd">
                                <td class="">
                                    <center>Comment</center>
                                </td>
                                <td class=" ">
                                    <center>
                                        <?php
                                            _echo($auctionFetcher['comment']);
                                        ?>
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <div id="panel_tab2_example2" class="tab-pane">
                        <table class="table table-bordered table-striped table-hover data-table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Bid</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $getAllBids = $odb->prepare("SELECT * FROM `bids` WHERE `auctionid` = ?");
                                    $getAllBids->BindValue(1, $auctionFetcher['id']);
                                    $getAllBids->execute();
                        
                                    while($record = $getAllBids->fetch(PDO::FETCH_ASSOC)){
                                        ?>
                                            <tr>
                                                <td><center><?php _echo($TibiaMarket->characterTable($record['charid'], "name"));?></center></td>
                                                <td><center><?php _echo($record['bid']);?></center></td>
                                                <td><center><?php _echo($record['date']);?></center></td>
                                            </tr>
                                        <?php
                                    }
                                ?>                      
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-1"></div>
        <div class="col-sm-4" style="top: 45px;">
            <div class="widget-box">
                <div class="widget-title">
                    <span class="icon">
                        <i class="fa fa-star"></i>
                    </span>
                    <h5>Make a bid</h5>
                </div>
                <div class="widget-content nopadding">
                    <form action="" method="post" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Current Bid</label>
                            <div class="col-sm-9">
                                <label class="control-label" style="font-weight: normal;"><?php _echo($currentBid);?></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Character</label>
                            <div class="col-sm-9">
                                <select name="charFromBid">
                                    <?php
                                        $getAllChars = $odb->prepare("SELECT * FROM `characters` WHERE `userid` = ? AND `status` = ?");
                                        $getAllChars->BindValue(1, $userData['id']);
                                        $getAllChars->BindValue(2, "1");
                                        $getAllChars->execute();

                                        while($record = $getAllChars->fetch(PDO::FETCH_ASSOC)){
                                            ?>
                                                <option value="<?php _echo($record['id']);?>"> <?php _echo($record['name']);?> </option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Your Bid</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control input-sm" name="mybidding">
                            </div>
                        </div>
                    
                        <div class="form-actions">
                            <?php

                                if($auctionFetcher['status'] == "0"){               
                                    if($getVerifiedCharCount->rowCount() == 0){
                                        echo '<button type="submit" class="btn btn-primary btn-sm">You do not have a verified character.</button>';
                                    }else{
                                        echo '<button type="submit" class="btn btn-primary btn-sm">Submit</button>';
                                    }
                                }else{
                                    echo '<button style="margin-left: -105px;" type="submit" class="btn btn-primary btn-sm">Bidding has been ended. Winner is: ';
                                    _echo($TibiaMarket->userTable($auctionFetcher['winner'], "username"));
                                    echo '</button>';
                                }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
		<?php } else { echo("This is pending, mate."); } ?>
    </div>
</div>

<?php
include('._._inc_footer._._.php');
?>