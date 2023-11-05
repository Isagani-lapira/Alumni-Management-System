import { postJSONFromURL, getJSONFromURL } from "../scripts/utils.js";

$(document).ready(function () {
  // Constants
  const GET_API_URL = "./alumni-of-the-month/getAlumni.php";
  const pwd = window.location.href;
  // split the pwd when there is the word college
  const splitPath = pwd.split("college");
  // get the first element of the split path
  const rootPath = splitPath[0];
  console.log(pwd, splitPath);

  const PROFILE_PICTURE_URL =
    rootPath + "media/search.php?media=profile_pic&personID=";
  const AOM_COVER_IMAGE_URL = rootPath + "media/search.php?media=aom&AOMID=";

  const API_URL_SEARCH = "php/searchAlumni.php?search=true";
  const API_POST_URL = "./alumni-of-the-month/addAlumni.php";
  const AVATAR_PLACEHOLDER = "../assets/default_profile.png";
});
