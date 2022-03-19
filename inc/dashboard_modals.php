                    
                    <!--edit call_line modal-->
                    <div class="modal fade" id="phone" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            <h3 class="modal-title">Edit your call line</h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                              <div class="form-group">
                              <input type="text" class="form-control" name="call_line"/>
                              </div>
                              <input type="submit" name="call_line_submit" class="btn btn-success" value="Make Changes"/>
                           </form>                         
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->
                      
                      <!--edit whatsapp modal-->
                    <div class="modal fade" id="whatsapp" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            <h3 class="modal-title">Edit your whatsapp contact</h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                              <div class="form-group">
                              <input type="text" class="form-control" name="whatsapp"/>
                              </div>
                              <input type="submit" name="whatsapp_submit" class="btn btn-success" value="Change My Whatsapp"/>
                           </form>                         
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->


                     <!--edit status modal-->
                    <div class="modal fade" id="hall" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            <h3 class="modal-title">Change Your Hall</h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                              <div class="form-group">
                                <select class="form-control" name="hall">
                                      <option value="default"></option>
                                    <option value="Jean Nelson Aka">Jean Nelson Aka</option>
                                    <option value="Alex Kwapong">Alex Kwapong</option>
                                    <option value="Hilla Limann">Hilla Limann</option>
                                    <option value="Elizabeth Sey">Elizabeth Sey</option>
                                    <option value="Ish 1">International Students Hostel 1</option>
                                    <option value="Ish 2">International Students Hostel 2</option>
                                    <option value="Jubilee">Jubilee</option>
                                    <option value="Pentagon Blk A">Pentagon Blk A</option>
                                    <option value="Pentagon Blk B">Pentagon Blk B</option>
                                    <option value="Pentagon Blk C">Pentagon Blk C</option>
                                    <option value="Old Pent">Old Pent</option>
                                    <option value="Bani">Bani</option>
                                    <option value="Evandy">Evandy</option>
                                    <option value="TF">TF</option>
                                    <option value="Volta">Volta</option>
                                    <option value="Sarbah Hall Main">Sarbah Hall (Main)</option>
                                    <option value="Sarbah Hall Annex A">Sarbah Hall (Annex A)</option>
                                    <option value="Sarbah Hall Annex B">Sarbah Hall (Annex B)</option>
                                    <option value="Sarbah Hall Annex C">Sarbah Hall (Annex C)</option>
                                    <option value="Sarbah Hall Annex D">Sarbah Hall (Annex D)</option>
                                    <option value="Akuafo Hall Main">Akuafo Hall (Main)</option>
                                    <option value="Akuafo Hall Annex A">Akuafo Hall (Annex A)</option>
                                    <option value="Akuafo Hall Annex B">Akuafo Hall (Annex B</option>
                                    <option value="Akuafo Hall Annex C">Akuafo Hall (Annex C)</option>
                                    <option value="Akuafo Hall Annex D">Akuafo Hall (Annex D)</option>
                                    <option value="Legon Hall Main">Legon Hall (Main)</option>
                                    <option value="Legon Hall Annex A">Legon Hall (Annex A)</option>
                                    <option value="Legon Hall Annex B">Legon Hall (Annex B)</option>
                                    <option value="Legon Hall Graduate Hostel">Legon Hall (Graduate Hostel)</option>
                                    <option value="Valco">Valco Hostel</option>
                                    <option value="Commonwealth">Commonwealth</option>
                                </select>
                              </div>
                              <input type="submit" name="hall_submit" class="btn btn-success" value="Change My Hall"/>
                           </form>                         
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->
                    
                     
                     <!--edit status modal-->
                    <div class="modal fade" id="mobile_money" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            <h3 class="modal-title">Edit Your Mobile Money Details</h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                              <div class="form-group">
                                <select class="form-control" name="mobile_vendor">
                                  <option value="default">Choose Vendor</option>
                                  <option value="Airtel Money">AIRTEL MONEY</option>
                                  <option value="MTN">MTN MOBILE MONEY</option>
                                  <option value="Vodafone Cash">VODAFONE CASH</option>
                                  <option value="Tigo Cash">TIGO CASH</option>
                                </select>
                              </div>
                              <div class="form-group">
                               <input type="text" placeholder="Enter account number" class="form-control" name="mobile_account"/>
                              </div>
                              <input type="submit" name="mobile_submit" class="btn btn-success" value="make changes"/>
                           </form>                         
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->

                            <!--edit status modal-->
                    <div class="modal fade" id="bank" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            <h3 class="modal-title"><i class="fa fa-lock"></i> Edit Your Bank Details</h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                               <div class="form-group">
                                <select class="form-control" name="bank_name">
                                  <option value="default">Choose Bank</option>
                                  <option value="Ecobank">Ecobank</option>
                                  <option value="Cal Bank">Cal Bank</option>
                                </select>
                              </div>
                              <div class="form-group">
                               <input type="text" placeholder="Enter Bank Account Name" class="form-control" name="bank_account_name"/>
                              </div>
                              <div class="form-group">
                                <input type="text" placeholder="Enter Bank Account Number" class="form-control" name="bank_account_number"/>
                              </div>
                              <input type="submit" name="bank_submit" class="btn btn-success" value="Make Changes"/>
                           </form>                         
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->
                     
                     <!--edit speciality modal-->
                    <div class="modal fade" id="username" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            <h3 class="modal-title"><i class="fa fa-lock"></i> Enter Your New Username</h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                              <div class="form-group">
                              <input type="text" class="form-control" name="new_username"/>
                              </div>
                              <input type="submit" name="username_submit" class="btn btn-success" value="Make Changes"/>
                           </form>                         
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->
               
              
                      
                    <!--edit fc modal-->
                    <div class="modal fade" id="password" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            <h3 class="modal-title"><i class="fa fa-lock"></i> Enter Your New Password</h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                              <div class="form-group">
                              <input type="password" class="form-control" name="new_password"/>
                              </div>
                              <input type="submit" name="password_submit" class="btn btn-success" value="Change Password"/>
                           </form>                         
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->


                          <!--edit firstname modal-->
                    <div class="modal fade" id="about" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            <h3 class="modal-title">Write Something About Yourself &amp; Your Business</h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                              <div class="form-group">
                              <textarea class="form-control" name="about" style="max-width:100%;"><?php echo $seller_about; ?></textarea>
                              </div>
                              <input type="submit" name="about_submit" class="btn btn-success" value="Write My About"/>
                           </form>                         
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->
                    

                                <!--edit email modal-->
                    <div class="modal fade" id="email" tabindex="-1"><!--modal div begins-->
                      <div class="modal-dialog"><!--modal dialog begins-->
                        <div class="modal-content"><!-- modal-content begins -->
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            <h3 class="modal-title">Kindly Change Your Email Here</h3>
                          </div>
                          <div class="modal-body"><!-- modal-body begins -->
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                              <div class="form-group">
                              <input type="email" class="form-control" name="new_email"/>
                              </div>
                              <input type="submit" name="email_submit" class="btn btn-success" value="Change My Email"/>
                           </form>                         
                          </div><!-- modal-body ends -->
                        </div><!-- modal-content ends -->
                      </div><!--modal dialog ends-->
                    </div><!--modal div ends-->
                    
                    

