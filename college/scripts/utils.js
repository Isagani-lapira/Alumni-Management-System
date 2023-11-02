export async function getJSONFromURL(url) {
  const response = await fetch(url, {
    headers: {
      method: "GET",
      "Content-Type": "application/json",
      cache: "no-cache",
    },
  });
  const result = await response.json();
  return result;
}

export async function postJSONFromURL(url, formData = null) {
  const response = await fetch(url, {
    method: "POST",
    body: formData,
  });
  const result = await response.json();
  return result;
}

/**
 * Animates opacity transition on a container to show/hide elements with CSS classes.
 *
 * ! Requires jQuery.
 *
 * @param {jQuery} container - The container element to be animated.
 * @param {jQuery} hiddenElem - The element to hide.
 * @param {jQuery} hideElem - The element to show.
 * @param {Object} options - The options object.
 * @param {string} options.toggledClassName - The class name to toggle.
 * @param {number} options.delayTime - The delay time.
 * @param {number} options.duration - The duration of the animation.
 * @returns {void}
 */
export function animateOpactityTransitionOnContainer(
  container,
  hiddenElem,
  hideElem,
  options = { toggledClassName: "hidden", delayTime: 50, duration: 300 }
) {
  container.css({
    opacity: "0.0",
  });

  // get the options
  const { toggledClassName, delayTime, duration } = options;

  // remove all the active classes
  hiddenElem.removeClass(toggledClassName);
  hideElem.addClass(toggledClassName);

  // animate the container to show the new element
  container.delay(delayTime).animate(
    {
      opacity: "1.0",
    },
    duration
  );
}
